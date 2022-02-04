<?xml version="1.0" encoding="utf-8"?>
<!--
/**
 * This file is part of TETHYS. 
 *
 * LICENCE
 * TETHYS is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the Licence, or any later version.
 * OPUS is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details. You should have received a copy of the GNU General Public License
 * along with OPUS; if not, write to the Free Software Foundation, Inc., 51
 * Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * @category    Application
 * @package     Module_Oai
 * @author      Arno Kaimbacher <arno.kaimbacher@geologie.ac.at>
 * @copyright   Copyright (c) 2018-2019, GBA TETHYS development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 */
-->

<!--
/**
 * Transforms the xml representation of an TETHYS model dataset to datacite
 * xml as required by the OAI-PMH protocol.
 */
-->
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:str="http://exslt.org/strings"
    xmlns:oai_dc="http://www.openarchives.org/OAI/2.0/oai_dc/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:php="http://php.net/xsl" exclude-result-prefixes="php">

    <xsl:output method="xml" indent="yes" />

    <xsl:template match="Rdr_Dataset" mode="oai_datacite">
        <resource xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xmlns="http://datacite.org/schema/kernel-4" xsi:schemaLocation="http://datacite.org/schema/kernel-4 http://schema.datacite.org/meta/kernel-4.3/metadata.xsd">
            <!-- <isReferenceQuality>true</isReferenceQuality>
      <schemaVersion>4.3</schemaVersion>
      <datacentreSymbol>RDR.GBA</datacentreSymbol> -->
            <xsl:choose>
                <xsl:when test="Identifier">
                    <xsl:apply-templates select="Identifier" mode="oai_datacite" />
                </xsl:when>
                <xsl:otherwise>
                    <identifier>
                        <xsl:text>oai:</xsl:text>
                        <xsl:value-of select="$repIdentifier" />
                        <xsl:text>:</xsl:text>
                        <xsl:value-of select="@PublishId" />
                    </identifier>
                </xsl:otherwise>
            </xsl:choose>

            <!--<datacite:creator>-->
            <creators>
                <xsl:apply-templates select="PersonAuthor" mode="oai_datacite" />
            </creators>
            <titles>
                <xsl:apply-templates select="TitleMain" mode="oai_datacite" />
                <xsl:apply-templates select="TitleAdditional" mode="oai_datacite" />
            </titles>
            <publisher>
                <!-- <xsl:value-of select="@PublisherName" /> -->
                <xsl:value-of select="@CreatingCorporation" />
            </publisher>
            <publicationYear>
                <xsl:value-of select="ServerDatePublished/@Year"/>
            </publicationYear>
            <subjects>
                <xsl:apply-templates select="Subject" mode="oai_datacite" />
            </subjects>
            <language>
                <xsl:value-of select="@Language" />
            </language>
            <xsl:if test="PersonContributor">
                <contributors>
                    <xsl:apply-templates select="PersonContributor" mode="oai_datacite" />
                </contributors>
            </xsl:if>
            <dates>
                <xsl:call-template name="RdrDate2" mode="oai_datacite" />
            </dates>
            <resourceType resourceTypeGeneral="Dataset">
                <xsl:text>Dataset</xsl:text>
                <!-- <xsl:value-of select="@Type" /> -->
            </resourceType>

            <alternateIdentifiers>
                <xsl:call-template name="AlternateIdentifier"/>
            </alternateIdentifiers>

            <xsl:if test="Reference">
                <relatedIdentifiers>
                    <xsl:apply-templates select="Reference" mode="oai_datacite" />
                </relatedIdentifiers>
            </xsl:if>
            <rightsList>
                <xsl:apply-templates select="Licence" mode="oai_datacite" />
            </rightsList>
            <sizes>
                <size>
                    <xsl:value-of select="count(File)"/>
                    <xsl:text> datasets</xsl:text>
                </size>
            </sizes>
            <formats>
                <xsl:apply-templates select="File/@MimeType" mode="oai_datacite" />
            </formats>
            <descriptions>
                <xsl:apply-templates select="TitleAbstract" mode="oai_datacite" />
                <xsl:apply-templates select="TitleAbstractAdditional" mode="oai_datacite" />
            </descriptions>
            <geoLocations>
                <xsl:apply-templates select="Coverage" mode="oai_datacite" />
                <!-- <geoLocation>
                    <geoLocationBox>
                        <westBoundLongitude>6.58987</westBoundLongitude>
                        <eastBoundLongitude>6.83639</eastBoundLongitude>
                        <southBoundLatitude>50.16</southBoundLatitude>
                        <northBoundLatitude>50.18691</northBoundLatitude>
                    </geoLocationBox>
                </geoLocation> -->
            </geoLocations>
        </resource>
    </xsl:template>

    <xsl:template name="RdrDate2" mode="oai_datacite"
        xmlns="http://datacite.org/schema/kernel-4">
        <xsl:if test="EmbargoDate and ($unixTimestamp &lt; EmbargoDate/@UnixTimestamp)">
            <date>
                <xsl:attribute name="dateType">Available</xsl:attribute>
                <xsl:variable name="embargoDate" select="concat(
                    EmbargoDate/@Year, '-',
                    format-number(EmbargoDate/@Month,'00'), '-',
                    format-number(EmbargoDate/@Day,'00')     
                )" />
                <xsl:value-of select="$embargoDate" />
            </date>
        </xsl:if>
        <xsl:if test="CreatedAt">
            <date>
                <xsl:attribute name="dateType">created</xsl:attribute>
                <xsl:variable name="createdAt" select="concat(
                    CreatedAt/@Year, '-',
                    format-number(CreatedAt/@Month,'00'), '-',
                    format-number(CreatedAt/@Day,'00')        
                )" />
                <xsl:value-of select="$createdAt" />
            </date>
        </xsl:if>
    </xsl:template>

    <xsl:template match="Coverage" mode="oai_datacite"
        xmlns="http://datacite.org/schema/kernel-4">
        <geoLocation>
            <geoLocationBox>
                <westBoundLongitude>
                    <xsl:value-of select="@XMin" />
                </westBoundLongitude>
                <eastBoundLongitude>
                    <xsl:value-of select="@XMax" />
                </eastBoundLongitude>
                <southBoundLatitude>
                    <xsl:value-of select="@YMin" />
                </southBoundLatitude>
                <northBoundLatitude>
                    <xsl:value-of select="@YMax" />
                </northBoundLatitude>
            </geoLocationBox>
        </geoLocation>
    </xsl:template>

    <xsl:template match="TitleAbstract" mode="oai_datacite"
        xmlns="http://datacite.org/schema/kernel-4">
        <description>
            <xsl:attribute name="xml:lang">
                <xsl:value-of select="@Language" />
            </xsl:attribute>
            <xsl:if test="@Type != ''">
                <xsl:attribute name="descriptionType">
                    <!-- <xsl:value-of select="@Type" /> -->
                    <xsl:text>Abstract</xsl:text>
                </xsl:attribute>
            </xsl:if>
            <xsl:value-of select="@Value" />
        </description>
    </xsl:template>
    <xsl:template match="TitleAbstractAdditional" mode="oai_datacite"
        xmlns="http://datacite.org/schema/kernel-4">
        <description>
            <xsl:attribute name="xml:lang">
                <xsl:value-of select="@Language" />
            </xsl:attribute>
            <xsl:if test="@Type != ''">
                <xsl:attribute name="descriptionType">
                    <xsl:call-template name="CamelCaseWord">
                        <xsl:with-param name="text" select="@Type"/>
                    </xsl:call-template>
                </xsl:attribute>
            </xsl:if>
            <xsl:value-of select="@Value" />
        </description>
    </xsl:template>


    <xsl:template name="CamelCaseWord">
        <xsl:param name="text"/>
        <xsl:param name="firstLower" select="true()"/>
        <xsl:variable name="Upper">ABCDEFGHIJKLMNOPQRSTUVQXYZ</xsl:variable>
        <xsl:variable name="Lower">abcdefghijklmnopqrstuvwxyz</xsl:variable>
        <xsl:for-each select="str:split($text,'_')">
            <xsl:choose>
                <xsl:when test="position()=1 and $firstLower = true()">
                    <xsl:value-of select="substring(node(),1,1)"/>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="translate(substring(node(),1,1),$Lower,$Upper)"/>
                </xsl:otherwise>
            </xsl:choose>
            <xsl:value-of select="substring(node(),2,string-length(node()))"/>
        </xsl:for-each>
    </xsl:template>

    <xsl:template match="Identifier" mode="oai_datacite"
        xmlns="http://datacite.org/schema/kernel-4">
        <identifier>
            <xsl:attribute name="identifierType">
                <xsl:text>DOI</xsl:text>
            </xsl:attribute>
            <xsl:value-of select="@Value" />
        </identifier>
    </xsl:template>

    <xsl:template match="TitleMain" mode="oai_datacite"
        xmlns="http://datacite.org/schema/kernel-4">
        <title>
            <xsl:if test="@Language != ''">
                <xsl:attribute name="xml:lang">
                    <xsl:value-of select="@Language" />
                </xsl:attribute>
            </xsl:if>
            <xsl:if test="@Type != '' and @Type != 'Main'">
                <xsl:attribute name="titleType">
                    <xsl:value-of select="@Type" />
                </xsl:attribute>
            </xsl:if>
            <xsl:value-of select="@Value"/>
        </title>
    </xsl:template>
    <xsl:template match="TitleAdditional" mode="oai_datacite"
        xmlns="http://datacite.org/schema/kernel-4">
        <title>
            <xsl:if test="@Language != ''">
                <xsl:attribute name="xml:lang">
                    <xsl:value-of select="@Language" />
                </xsl:attribute>
            </xsl:if>
            <xsl:choose>
                <xsl:when test="@Type != '' and @Type != 'Sub' and @Type != 'Main'">
                    <xsl:attribute name="titleType">
                        <xsl:value-of select="@Type" />
                        <xsl:text>Title</xsl:text>
                    </xsl:attribute>
                </xsl:when>
                <xsl:when test="@Type = 'Sub'">
                    <xsl:attribute name="titleType">
                        <xsl:value-of select="@Type" />
                        <xsl:text>title</xsl:text>
                    </xsl:attribute>
                </xsl:when>
            </xsl:choose>
            <xsl:value-of select="@Value"/>
        </title>
    </xsl:template>

    <xsl:template match="Subject" mode="oai_datacite"
        xmlns="http://datacite.org/schema/kernel-4">
        <subject>
            <xsl:if test="@Language != ''">
                <xsl:attribute name="xml:lang">
                    <xsl:value-of select="@Language" />
                </xsl:attribute>
            </xsl:if>
            <xsl:value-of select="@Value" />
        </subject>
    </xsl:template>

    <xsl:template name="AlternateIdentifier" mode="oai_datacite"
        xmlns="http://datacite.org/schema/kernel-4">
        <alternateIdentifier >
            <xsl:attribute name="alternateIdentifierType">
                <xsl:text>url</xsl:text>
            </xsl:attribute>
            <!-- <xsl:variable name="identifier" select="concat($repURL, '/dataset/', @Id)" /> -->
            <xsl:value-of select="@landingpage"/>
        </alternateIdentifier >
    </xsl:template>

    <xsl:template match="Reference" mode="oai_datacite"
        xmlns="http://datacite.org/schema/kernel-4">
        <relatedIdentifier>
            <xsl:attribute name="relatedIdentifierType">
                <xsl:value-of select="@Type" />
            </xsl:attribute>
            <xsl:attribute name="relationType">
                <xsl:value-of select="@Relation" />
            </xsl:attribute>
            <xsl:value-of select="@Value" />
        </relatedIdentifier>
    </xsl:template>

    <xsl:template match="PersonContributor" mode="oai_datacite"
        xmlns="http://datacite.org/schema/kernel-4">
        <contributor>
            <xsl:if test="@ContributorType != ''">
                <xsl:attribute name="contributorType">
                    <xsl:value-of select="@ContributorType" />
                </xsl:attribute>
            </xsl:if>
            <contributorName>
                <!-- <xsl:if test="@NameType != ''">
                    <xsl:attribute name="nameType">
                        <xsl:value-of select="@NameType" />
                    </xsl:attribute>
                </xsl:if> -->
                <xsl:value-of select="concat(@FirstName, ' ',@LastName)" />
            </contributorName>
        </contributor>
    </xsl:template>

    <xsl:template match="PersonAuthor" mode="oai_datacite"
        xmlns="http://datacite.org/schema/kernel-4">
        <creator>
            <creatorName>
                <xsl:if test="@NameType != ''">
                    <xsl:attribute name="nameType">
                        <xsl:value-of select="@NameType" />
                    </xsl:attribute>
                </xsl:if>
                <xsl:value-of select="@LastName" />
                <xsl:if test="@FirstName != ''">
                    <xsl:text>, </xsl:text>
                </xsl:if>
                <xsl:value-of select="@FirstName" />
                <xsl:if test="@AcademicTitle != ''">
                    <xsl:text> (</xsl:text>
                    <xsl:value-of select="@AcademicTitle" />
                    <xsl:text>)</xsl:text>
                </xsl:if>
            </creatorName>

            <xsl:if test="@NameType = 'Personal'">
                <givenName>
                    <xsl:value-of select="@FirstName" />
                </givenName>
                <familyName>
                    <xsl:value-of select="@LastName" />
                </familyName>
                <affiliation>GBA</affiliation>
            </xsl:if>

            <xsl:if test="@IdentifierOrcid != ''">
                <nameIdentifier schemeURI="http://orcid.org/" nameIdentifierScheme="ORCID">
                    <xsl:value-of select="@IdentifierOrcid" />
                </nameIdentifier>
            </xsl:if>
            <!-- 
        <nameType><xsl:value-of select="@NameType" /></nameType>
      </xsl:if> -->

        </creator>
    </xsl:template>

    <xsl:template match="File/@MimeType" mode="oai_datacite"
        xmlns="http://datacite.org/schema/kernel-4">
        <format>
            <xsl:choose>
                <xsl:when test=". = 'application/x-sqlite3'">
                    <xsl:text>application/geopackage+sqlite3</xsl:text>
                </xsl:when>
                <xsl:otherwise>
                    <xsl:value-of select="." />
                </xsl:otherwise>
            </xsl:choose>
        </format>
    </xsl:template>

    <xsl:template match="Licence" mode="oai_datacite"
        xmlns="http://datacite.org/schema/kernel-4">
        <rights>
            <xsl:attribute name="xml:lang">
                <xsl:value-of select="@Language" />
            </xsl:attribute>
            <xsl:if test="@LinkLicence != ''">
                <xsl:attribute name="rightsURI">
                    <xsl:value-of select="@LinkLicence" />
                </xsl:attribute>
            </xsl:if>
            <xsl:attribute name="schemeURI">
                <xsl:text>https://spdx.org/licenses/</xsl:text>
            </xsl:attribute>
            <xsl:attribute name="rightsIdentifierScheme">
                <xsl:text>SPDX</xsl:text>
            </xsl:attribute>
            <xsl:attribute name="rightsIdentifier">
                <xsl:value-of select="@Name" />
            </xsl:attribute>
            <xsl:value-of select="@NameLong" />
        </rights>
        <xsl:if test="@Name = 'CC-BY-4.0' or @Name = 'CC-BY-SA-4.0'">
            <rights>
                <xsl:attribute name="rightsURI">
                    <xsl:text>info:eu-repo/semantics/openAccess</xsl:text>
                </xsl:attribute>
                <xsl:text>Open Access</xsl:text>
            </rights>
        </xsl:if>
    </xsl:template>

</xsl:stylesheet>