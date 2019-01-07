<?xml version="1.0" encoding="utf-8"?>
<!--
/**
 * This file is part of RDR. 
 *
 * LICENCE
 * RDR is free software; you can redistribute it and/or modify it under the
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
 * @copyright   Copyright (c) 2018-2019, GBA RDR development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 */
-->

<!--
/**
 * Transforms the xml representation of an RDR model dataset to datacite
 * xml as required by the OAI-PMH protocol.
 */
-->
<xsl:stylesheet version="1.0" 
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
    xmlns:oai_dc="http://www.openarchives.org/OAI/2.0/oai_dc/" 
    xmlns:dc="http://purl.org/dc/elements/1.1/" 
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" 
    xmlns:php="http://php.net/xsl" exclude-result-prefixes="php">

    <xsl:output method="xml" indent="yes" />

    <xsl:template match="Rdr_Dataset" mode="oai_datacite">
        <resource xmlns="http://datacite.org/schema/kernel-4" 
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://datacite.org/schema/kernel-4 http://schema.datacite.org/meta/kernel-4.1/metadata.xsd">
            <!-- <isReferenceQuality>true</isReferenceQuality>
      <schemaVersion>4.1</schemaVersion>
      <datacentreSymbol>RDR.GBA</datacentreSymbol> -->
            <identifier>
                <xsl:text>oai:</xsl:text>
                <xsl:value-of select="$repIdentifier" />
                <xsl:text>:</xsl:text>
                <xsl:value-of select="@Id" />
            </identifier>
            <!--<datacite:creator>-->
            <creators>
                <xsl:apply-templates select="PersonAuthor" mode="oai_datacite" />
            </creators>
            <titles>
                <xsl:apply-templates select="TitleMain" mode="oai_datacite" />
            </titles>
            <publisher>
                <xsl:value-of select="@PublisherName" />
            </publisher>
            <publicationYear>
                <xsl:value-of select="ServerDatePublished/@Year"/>
            </publicationYear>
            <language>
                <xsl:value-of select="@Language" />
            </language>
            <resourceType resourceTypeGeneral="Dataset">
            <xsl:text>Dataset</xsl:text>
                <!-- <xsl:value-of select="@Type" /> -->
            </resourceType>
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
            </descriptions>
            <geoLocations>
                <xsl:apply-templates select="GeolocationBox" mode="oai_datacite" />
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

    <xsl:template match="GeolocationBox" mode="oai_datacite">
        <geoLocation>
            <geoLocationBox>
                <westBoundLongitude>
                    <xsl:value-of select="@Xmin" />
                </westBoundLongitude>
                <eastBoundLongitude>
                    <xsl:value-of select="@Xmax" />
                </eastBoundLongitude>
                <southBoundLatitude>
                    <xsl:value-of select="@Ymin" />
                </southBoundLatitude>
                <northBoundLatitude>
                    <xsl:value-of select="@Ymax" />
                </northBoundLatitude>
            </geoLocationBox>
        </geoLocation>
    </xsl:template>

    <xsl:template match="TitleAbstract" mode="oai_datacite">
        <description>
            <xsl:attribute name="xml:lang">
                <xsl:value-of select="@Language" />
            </xsl:attribute>
            <xsl:if test="@Type != ''">
                <xsl:attribute name="descriptionType">
                    <xsl:value-of select="@Type" />
                </xsl:attribute>
            </xsl:if>
            <xsl:value-of select="@Value" />
        </description>
    </xsl:template>

    <xsl:template match="TitleMain" mode="oai_datacite">
        <title>
            <xsl:if test="@Language != ''">
                <xsl:attribute name="xml:lang">
                    <xsl:value-of select="@Language" />
                </xsl:attribute>
            </xsl:if>
            <xsl:if test="@Type != '' and @Type != 'main'">
                <xsl:attribute name="titleType">
                    <xsl:value-of select="@Type" />
                </xsl:attribute>
            </xsl:if>
            <xsl:value-of select="@Value"/>
        </title>
    </xsl:template>

    <xsl:template match="PersonAuthor" mode="oai_datacite">
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
            <givenName>
                <xsl:value-of select="@FirstName" />
            </givenName>
            <familyName>
                <xsl:value-of select="@LastName" />
            </familyName>
            <xsl:if test="@IdentifierOrcid != ''">
                <nameIdentifier schemeURI="http://orcid.org/" nameIdentifierScheme="ORCID">
                    <xsl:value-of select="@IdentifierOrcid" />
                </nameIdentifier>
            </xsl:if>
            <!-- 
        <nameType><xsl:value-of select="@NameType" /></nameType>
      </xsl:if> -->
            <affiliation>GBA</affiliation>
        </creator>
    </xsl:template>

    <xsl:template match="File/@MimeType" mode="oai_datacite">
        <format>
            <xsl:value-of select="." />
        </format>
    </xsl:template>

    <xsl:template match="Licence" mode="oai_datacite">
        <rights>
            <xsl:if test="@LinkLicence != ''">
                <xsl:attribute name="rightsURI">
                    <xsl:value-of select="@LinkLicence" />
                </xsl:attribute>
            </xsl:if>
            <xsl:value-of select="@NameLong" />
        </rights>
    </xsl:template>

</xsl:stylesheet>