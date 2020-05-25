<?xml version="1.0" encoding="utf-8"?>
<!--
/**
 * This file is part of OPUS. The software OPUS has been originally developed
 * at the University of Stuttgart with funding from the German Research Net,
 * the Federal Department of Higher Education and Research and the Ministry
 * of Science, Research and the Arts of the State of Baden-Wuerttemberg.
 *
 * OPUS 4 is a complete rewrite of the original OPUS software and was developed
 * by the Stuttgart University Library, the Library Service Center
 * Baden-Wuerttemberg, the North Rhine-Westphalian Library Service Center,
 * the Cooperative Library Network Berlin-Brandenburg, the Saarland University
 * and State Library, the Saxon State Library - Dresden State and University
 * Library, the Bielefeld University Library and the University Library of
 * Hamburg University of Technology with funding from the German Research
 * Foundation and the European Regional Development Fund.
 *
 * LICENCE
 * OPUS is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the Licence, or any later version.
 * OPUS is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details. You should have received a copy of the GNU General Public License 
 * along with OPUS; if not, write to the Free Software Foundation, Inc., 51 
 * Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 *
 * @category    Framework
 * @package     Opus_SolrSearch
 * @author      Oliver Marahrens <o.marahrens@tu-harburg.de>
 * @author      Sascha Szott <szott@zib.de>
 * @copyright   Copyright (c) 2008-2011, OPUS 4 development team
 * @license     http://www.gnu.org/licenses/gpl.html General Public License
 * @version     $Id$
 */
-->

<xsl:stylesheet version="1.0" 
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform" 
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:php="http://php.net/xsl">

    <xsl:output method="xml" indent="yes" />

    <!-- Suppress output for all elements that don't have an explicit template. -->
    <xsl:template match="*" />

    <xsl:template match="/">
        <xsl:element name="add">
            <xsl:element name="doc">

                <!-- id -->
                <xsl:element name="field">
                    <xsl:attribute name="name">id</xsl:attribute>
                    <xsl:value-of select="/Opus/Rdr_Dataset/@PublishId" />
                </xsl:element>

                <!-- year -->
                <xsl:variable name="year">
                    <xsl:choose>
                        <xsl:when test="/Opus/Rdr_Dataset/ServerDatePublished/@Year != ''">
                            <xsl:value-of select="/Opus/Rdr_Dataset/ServerDatePublished/@Year" />
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:value-of select="/Opus/Rdr_Dataset/@PublishedYear" />
                        </xsl:otherwise>
                    </xsl:choose>
                </xsl:variable>

                <xsl:element name="field">
                    <xsl:attribute name="name">year</xsl:attribute>
                    <xsl:value-of select="$year"/>
                </xsl:element>

                <!-- year inverted -->
                <xsl:if test="$year">
                    <xsl:variable name="yearInverted" select="65535 - $year"/>
                    <xsl:element name="field">
                        <xsl:attribute name="name">year_inverted</xsl:attribute>
                        <xsl:value-of select="$yearInverted"/>
:                        <xsl:value-of select="$year"/>
                    </xsl:element>
                </xsl:if>

                <!-- server_date_published -->
                <xsl:if test="/Opus/Rdr_Dataset/ServerDatePublished/@UnixTimestamp != ''">
                    <xsl:element name="field">
                        <xsl:attribute name="name">server_date_published</xsl:attribute>
                        <xsl:value-of select="/Opus/Rdr_Dataset/ServerDatePublished/@UnixTimestamp" />
                    </xsl:element>
                </xsl:if>

                <!-- server_date_modified -->
                <xsl:if test="/Opus/Rdr_Dataset/ServerDateModified/@UnixTimestamp != ''">
                    <xsl:element name="field">
                        <xsl:attribute name="name">server_date_modified</xsl:attribute>
                        <xsl:value-of select="/Opus/Rdr_Dataset/ServerDateModified/@UnixTimestamp" />
                    </xsl:element>
                </xsl:if>

                <!-- language -->
                <xsl:variable name="language" select="/Opus/Rdr_Dataset/@Language" />
                <xsl:element name="field">
                    <xsl:attribute name="name">language</xsl:attribute>
                    <xsl:value-of select="$language" />
                </xsl:element>

                <!-- title / title_output -->
                <xsl:for-each select="/Opus/Rdr_Dataset/TitleMain">
                    <xsl:element name="field">
                        <xsl:attribute name="name">title</xsl:attribute>
                        <xsl:value-of select="@Value" />
                    </xsl:element>
                    <xsl:if test="@Language = $language">
                        <xsl:element name="field">
                            <xsl:attribute name="name">title_output</xsl:attribute>
                            <xsl:value-of select="@Value" />
                        </xsl:element>
                    </xsl:if>
                </xsl:for-each>

                <!-- abstract / abstract_output -->
                <xsl:for-each select="/Opus/Rdr_Dataset/TitleAbstract">
                    <xsl:element name="field">
                        <xsl:attribute name="name">abstract</xsl:attribute>
                        <xsl:value-of select="@Value" />
                    </xsl:element>
                    <xsl:if test="@Language = $language">
                        <xsl:element name="field">
                            <xsl:attribute name="name">abstract_output</xsl:attribute>
                            <xsl:value-of select="@Value" />
                        </xsl:element>
                    </xsl:if>
                </xsl:for-each>

                <!-- author -->
                <xsl:for-each select="/Opus/Rdr_Dataset/PersonAuthor">
                    <xsl:element name="field">
                        <xsl:attribute name="name">author</xsl:attribute>
                        <xsl:value-of select="@LastName" />
                        <xsl:text>, </xsl:text>
                        <xsl:value-of select="@FirstName" />
                    </xsl:element>
                </xsl:for-each>

                <!-- author_sort -->
                <xsl:element name="field">
                    <xsl:attribute name="name">author_sort</xsl:attribute>
                    <xsl:for-each select="/Opus/Rdr_Dataset/PersonAuthor">
                        <xsl:value-of select="@LastName" />
                        <xsl:text></xsl:text>
                        <xsl:value-of select="@FirstName" />
                        <xsl:text></xsl:text>
                    </xsl:for-each>
                </xsl:element>

                <!-- fulltext -->
                <xsl:for-each select="/Opus/Rdr_Dataset/Fulltext_Index">
                    <xsl:element name="field">
                        <xsl:attribute name="name">fulltext</xsl:attribute>
                        <xsl:value-of select="." />
                    </xsl:element>
                </xsl:for-each>

                <!-- has fulltext -->
                <xsl:element name="field">
                    <xsl:attribute name="name">has_fulltext</xsl:attribute>
                    <xsl:value-of select="/Opus/Rdr_Dataset/Has_Fulltext" />
                </xsl:element>

                <!-- IDs der Dateien, die mit nicht leerem Resultat extrahiert werden konnten -->
                <xsl:for-each select="/Opus/Rdr_Dataset/Fulltext_ID_Success">
                    <xsl:element name="field">
                        <xsl:attribute name="name">fulltext_id_success</xsl:attribute>
                        <xsl:value-of select="."/>
                    </xsl:element>
                </xsl:for-each>

                <!-- IDs der Dateien, die nicht erfolgreich extrahiert werden konnten -->
                <xsl:for-each select="/Opus/Rdr_Dataset/Fulltext_ID_Failure">
                    <xsl:element name="field">
                        <xsl:attribute name="name">fulltext_id_failure</xsl:attribute>
                        <xsl:value-of select="."/>
                    </xsl:element>
                </xsl:for-each>

                <!-- referee -->
                <xsl:for-each select="/Opus/Rdr_Dataset/PersonReferee">
                    <xsl:element name="field">
                        <xsl:attribute name="name">referee</xsl:attribute>
                        <xsl:value-of select="@FirstName" />
                        <xsl:text></xsl:text>
                        <xsl:value-of select="@LastName" />
                    </xsl:element>
                </xsl:for-each>

                <!-- other persons (non-authors) -->
                <xsl:for-each select="/Opus/Rdr_Dataset/*">
                    <xsl:if test="local-name() != 'Person' and local-name() != 'PersonAuthor' and local-name() != 'PersonSubmitter' and substring(local-name(), 1, 6) = 'Person'">
                        <xsl:element name="field">
                            <xsl:attribute name="name">persons</xsl:attribute>
                            <xsl:value-of select="@FirstName" />
                            <xsl:text></xsl:text>
                            <xsl:value-of select="@LastName" />
                        </xsl:element>
                    </xsl:if>
                </xsl:for-each>

                <!-- datatype -->
                <xsl:element name="field">
                    <xsl:attribute name="name">doctype</xsl:attribute>
                    <xsl:value-of select="/Opus/Rdr_Dataset/@Type" />
                </xsl:element>

                <!-- subject (swd) -->
                <xsl:for-each select="/Opus/Rdr_Dataset/Subject[@Type = 'swd']">
                    <xsl:element name="field">
                        <xsl:attribute name="name">subject</xsl:attribute>
                        <xsl:value-of select="@Value" />
                    </xsl:element>
                </xsl:for-each>

                <!-- subject (uncontrolled) -->
                <xsl:for-each select="/Opus/Rdr_Dataset/Subject[@Type = 'Uncontrolled']">
                    <xsl:element name="field">
                        <xsl:attribute name="name">subject</xsl:attribute>
                        <xsl:value-of select="@Value" />
                    </xsl:element>
                </xsl:for-each>

                <!-- belongs_to_bibliography -->
                <xsl:element name="field">
                    <xsl:attribute name="name">belongs_to_bibliography</xsl:attribute>
                    <xsl:choose>
                        <xsl:when test="/Opus/Rdr_Dataset/@BelongsToBibliography = 0">
                            <xsl:text>false</xsl:text>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:text>true</xsl:text>
                        </xsl:otherwise>
                    </xsl:choose>
                </xsl:element>

                <!-- collections: project, app_area, institute, ids -->
                <xsl:for-each select="/Opus/Rdr_Dataset/Collection">
                    <xsl:choose>
                        <xsl:when test="@RoleName = 'projects'">
                            <xsl:element name="field">
                                <xsl:attribute name="name">project</xsl:attribute>
                                <xsl:value-of select="@Number" />
                            </xsl:element>
                            <xsl:element name="field">
                                <xsl:attribute name="name">app_area</xsl:attribute>
                                <xsl:value-of select="substring(@Number, 0, 2)" />
                            </xsl:element>
                        </xsl:when>
                        <xsl:when test="@RoleName = 'institutes'">
                            <xsl:element name="field">
                                <xsl:attribute name="name">institute</xsl:attribute>
                                <xsl:value-of select="@Name" />
                            </xsl:element>
                        </xsl:when>
                    </xsl:choose>

                    <xsl:element name="field">
                        <xsl:attribute name="name">collection_ids</xsl:attribute>
                        <xsl:value-of select="@Id" />
                    </xsl:element>
                </xsl:for-each>

                <!-- title parent -->
                <xsl:for-each select="/Opus/Rdr_Dataset/TitleParent">
                    <xsl:element name="field">
                        <xsl:attribute name="name">title_parent</xsl:attribute>
                        <xsl:value-of select="@Value" />
                    </xsl:element>
                </xsl:for-each>

                <!-- title sub -->
                <xsl:for-each select="/Opus/Rdr_Dataset/TitleSub">
                    <xsl:element name="field">
                        <xsl:attribute name="name">title_sub</xsl:attribute>
                        <xsl:value-of select="@Value" />
                    </xsl:element>
                </xsl:for-each>

                <!-- title additional -->
                <xsl:for-each select="/Opus/Rdr_Dataset/TitleAdditional">
                    <xsl:element name="field">
                        <xsl:attribute name="name">title_additional</xsl:attribute>
                        <xsl:value-of select="@Value" />
                    </xsl:element>
                </xsl:for-each>

                <!-- abstract additional -->
                <xsl:for-each select="/Opus/Rdr_Dataset/TitleAbstractAdditional">
                    <xsl:element name="field">
                        <xsl:attribute name="name">abstract_additional</xsl:attribute>
                        <xsl:value-of select="@Value" />
                    </xsl:element>
                </xsl:for-each>

                <!-- licences -->
                <xsl:if test="/Opus/Rdr_Dataset/Licence">
                    <xsl:element name="field">
                        <xsl:attribute name="name">licence</xsl:attribute>
                        <xsl:value-of select="/Opus/Rdr_Dataset/Licence/@Name" />
                    </xsl:element>
                </xsl:if>

                <!-- series ids and series number per id (modeled as dynamic field) -->
                <xsl:for-each select="/Opus/Rdr_Dataset/Series">
                    <xsl:element name="field">
                        <xsl:attribute name="name">series_ids</xsl:attribute>
                        <xsl:value-of select="@Id"/>
                    </xsl:element>

                    <xsl:element name="field">
                        <xsl:attribute name="name">
                            <xsl:text>series_number_for_id_</xsl:text>
                            <xsl:value-of select="@Id"/>
                        </xsl:attribute>
                        <xsl:value-of select="@Number"/>
                    </xsl:element>

                    <xsl:element name="field">
                        <xsl:attribute name="name">
                            <xsl:text>doc_sort_order_for_seriesid_</xsl:text>
                            <xsl:value-of select="@Id"/>
                        </xsl:attribute>
                        <xsl:value-of select="@DocSortOrder"/>
                    </xsl:element>
                </xsl:for-each>

                <!-- creating corporation (single valued) -->
                <xsl:if test="/Opus/Rdr_Dataset/@CreatingCorporation">
                    <xsl:element name="field">
                        <xsl:attribute name="name">creating_corporation</xsl:attribute>
                        <xsl:value-of select="/Opus/Rdr_Dataset/@CreatingCorporation"/>
                    </xsl:element>
                </xsl:if>

                <!-- contributing corporation (single valued) -->
                <xsl:if test="/Opus/Rdr_Dataset/@ContributingCorporation">
                    <xsl:element name="field">
                        <xsl:attribute name="name">contributing_corporation</xsl:attribute>
                        <xsl:value-of select="/Opus/Rdr_Dataset/@ContributingCorporation"/>
                    </xsl:element>
                </xsl:if>

                <!-- publisher name (single valued) -->
                <xsl:if test="/Opus/Rdr_Dataset/@PublisherName">
                    <xsl:element name="field">
                        <xsl:attribute name="name">publisher_name</xsl:attribute>
                        <xsl:value-of select="/Opus/Rdr_Dataset/@PublisherName"/>
                    </xsl:element>
                </xsl:if>

                <!-- publisher place (single valued) -->
                <xsl:if test="/Opus/Rdr_Dataset/@PublisherPlace">
                    <xsl:element name="field">
                        <xsl:attribute name="name">publisher_place</xsl:attribute>
                        <xsl:value-of select="/Opus/Rdr_Dataset/@PublisherPlace"/>
                    </xsl:element>
                </xsl:if>

                <xsl:if test="/Opus/Rdr_Dataset/Coverage">
                    <xsl:element name="field">
                        <xsl:attribute name="name">geo_location</xsl:attribute>
                        <xsl:variable name="geolocation" select="concat(
                        'SOUTH-BOUND LATITUDE: ', /Opus/Rdr_Dataset/Coverage/@XMin,
                        ' * WEST-BOUND LONGITUDE: ', /Opus/Rdr_Dataset/Coverage/@YMin,
                        ' * NORTH-BOUND LATITUDE: ', /Opus/Rdr_Dataset/Coverage/@XMax,
                        ' * EAST-BOUND LONGITUDE: ', /Opus/Rdr_Dataset/Coverage/@YMax
                        )" />
                        <!-- <xsl:variable name="geolocation" select="concat('test', /Opus/Rdr_Dataset/Coverage/@XMin)" /> -->
                        <xsl:value-of select="$geolocation" />
                        <xsl:text>&#xA;</xsl:text>
                        <xsl:if test="@ElevationMin != '' and @ElevationMax != ''">
                            <xsl:value-of select="concat(' * ELEVATION MIN: ', @ElevationMin, ' * ELEVATION MAX: ', @ElevationMax)" />
                        </xsl:if>
                        <xsl:if test="@ElevationAbsolut != ''">
                            <xsl:value-of select="concat(' * ELEVATION ABSOLUT: ', @ElevationAbsolut)" />
                        </xsl:if>

                        <xsl:text>&#xA;</xsl:text>
                        <xsl:if test="@DepthMin != '' and @DepthMax != ''">
                            <xsl:value-of select="concat(' * DEPTH MIN: ', @DepthMin, ' * DEPTH MAX: ', @DepthMax)" />
                        </xsl:if>
                        <xsl:if test="@DepthAbsolut != ''">
                            <xsl:value-of select="concat(' * DEPTH ABSOLUT: ', @DepthAbsolut)" />
                        </xsl:if>

                        <xsl:text>&#xA;</xsl:text>
                        <xsl:if test="@TimeMin != '' and @TimeMax != ''">
                            <xsl:value-of select="concat(' * TIME MIN: ', @TimeMin, ' * TIME MAX: ', @TimeMax)" />
                        </xsl:if>
                        <xsl:if test="@TimeAbsolut != ''">
                            <xsl:value-of select="concat(' * TIME ABSOLUT: ', @TimeAbsolut)" />
                        </xsl:if>
                    </xsl:element>
                </xsl:if>

                <!-- identifier (multi valued) -->
                <xsl:for-each select="/Opus/Rdr_Dataset/Identifier">
                    <xsl:element name="field">
                        <xsl:attribute name="name">identifier</xsl:attribute>
                        <xsl:value-of select="@Value"/>
                    </xsl:element>
                </xsl:for-each>

                <!-- identifier (multi valued) -->
                <xsl:for-each select="/Opus/Rdr_Dataset/Reference">
                    <xsl:element name="field">
                        <xsl:attribute name="name">reference</xsl:attribute>
                        <xsl:value-of select="@Value"/>
                    </xsl:element>
                </xsl:for-each>

            </xsl:element>
        </xsl:element>

    </xsl:template>

</xsl:stylesheet>