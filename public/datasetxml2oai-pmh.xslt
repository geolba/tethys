<?xml version="1.0" encoding="utf-8"?>

<xsl:stylesheet version="1.0"
  xmlns="http://www.openarchives.org/OAI/2.0/"
  xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xmlns:dc="http://purl.org/dc/elements/1.1/"
  xmlns:oai_dc="http://www.openarchives.org/OAI/2.0/oai_dc/"
  xmlns:php="http://php.net/xsl">

  <!--<xsl:param name="urnResolverUrl" />-->

  <!-- add include here for each new metadata format  -->
  <xsl:include href="prefixes/oai_datacite.xslt" />


  <xsl:output method="xml" indent="yes" encoding="utf-8" />

  <xsl:param name="responseDate" />
  <xsl:param name="unixTimestamp" />
  <xsl:param name="email" />
  <xsl:param name="earliestDatestamp" />
  <xsl:param name="setPubType" />
  <xsl:param name="repositoryName" />
  <xsl:param name="repIdentifier" />
  <xsl:param name="downloadLink" />
  <xsl:param name="doiLink" />
  <xsl:param name="doiPrefix" />
  <xsl:param name="sampleIdentifier" />
  <xsl:param name="docId" />
  <xsl:param name="dateDelete" />
  <xsl:param name="totalIds" />
  <xsl:param name="res" />
  <xsl:param name="cursor" />
  <xsl:param name="oai_verb" />
  <xsl:param name="oai_from" />
  <xsl:param name="oai_until" />
  <xsl:param name="oai_set" />
  <xsl:param name="oai_metadataPrefix" />
  <xsl:param name="oai_resumptionToken" />
  <xsl:param name="oai_identifier" />
  <xsl:param name="oai_error_code" />
  <xsl:param name="oai_error_message" />
  <xsl:param name="baseURL" />
  <xsl:param name="repURL" />



  <!--create the head of oai response  -->
  <xsl:template match="/">
    <!-- stylesheet for browser -->
    <xsl:processing-instruction name="xml-stylesheet">
      <xsl:text>type="text/xsl" href="xsl/oai2_style.xslt"</xsl:text>
    </xsl:processing-instruction>

    <OAI-PMH xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xmlns="http://www.openarchives.org/OAI/2.0/" xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd">
      <responseDate>
        <xsl:value-of select="$responseDate" />
      </responseDate>
      <request>
        <xsl:if test="$oai_verb != ''">
          <xsl:attribute name="verb">
            <xsl:value-of select="$oai_verb" />
          </xsl:attribute>
        </xsl:if>
        <xsl:if test="$oai_metadataPrefix != ''">
          <xsl:attribute name="metadataPrefix">
            <xsl:value-of select="$oai_metadataPrefix" />
          </xsl:attribute>
        </xsl:if>
        <xsl:value-of select="$baseURL" />
      </request>
      <xsl:if test="$oai_error_code!=''">
        <error>
          <xsl:attribute name="code">
            <xsl:value-of select="$oai_error_code" />
          </xsl:attribute>
          <xsl:value-of select="$oai_error_message" />
        </error>
      </xsl:if>

      <!--create the rest of oai response depending on oai_verb -->
      <xsl:choose>
        <xsl:when test="$oai_verb='GetRecord'">
          <xsl:apply-templates select="Datasets" mode="GetRecord" />
        </xsl:when>
        <xsl:when test="$oai_verb='Identify'">
          <xsl:apply-templates select="Datasets" mode="Identify" />
        </xsl:when>
        <xsl:when test="$oai_verb='ListIdentifiers'">
          <xsl:apply-templates select="Datasets" mode="ListIdentifiers" />
        </xsl:when>
        <xsl:when test="$oai_verb='ListMetadataFormats'">
          <xsl:apply-templates select="Datasets" mode="ListMetadataFormats" />
        </xsl:when>
        <xsl:when test="$oai_verb='ListRecords'">
          <xsl:apply-templates select="Datasets" mode="ListRecords" />
        </xsl:when>
        <xsl:when test="$oai_verb='ListSets'">
          <xsl:apply-templates select="Datasets" mode="ListSets" />
        </xsl:when>
      </xsl:choose>
    </OAI-PMH>
  </xsl:template>


  <!-- template for Identiy  -->
  <xsl:template match="Datasets" mode="Identify">
    <Identify>
      <repositoryName>
        <xsl:value-of select="$repositoryName"/>
      </repositoryName>
      <baseURL>
        <xsl:value-of select="$baseURL"/>
      </baseURL>
      <protocolVersion>
        <xsl:text>2.0</xsl:text>
      </protocolVersion>
      <adminEmail>
        <xsl:value-of select="$email"/>
      </adminEmail>
      <earliestDatestamp>
        <xsl:value-of select="$earliestDatestamp"/>
      </earliestDatestamp>
      <deletedRecord>
        <xsl:text>persistent</xsl:text>
      </deletedRecord>
      <granularity>
        <xsl:text>YYYY-MM-DDThh:mm:ssZ</xsl:text>
      </granularity>
      <description>
        <oai-identifier xmlns="http://www.openarchives.org/OAI/2.0/oai-identifier" xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/oai-identifier http://www.openarchives.org/OAI/2.0/oai-identifier.xsd">
          <scheme>
            <xsl:text>oai</xsl:text>
          </scheme>
          <repositoryIdentifier>
            <xsl:value-of select="$repIdentifier"/>
          </repositoryIdentifier>
          <delimiter>
            <xsl:text>:</xsl:text>
          </delimiter>
          <sampleIdentifier>
            <xsl:value-of select="$sampleIdentifier"/>
          </sampleIdentifier>
        </oai-identifier>
      </description>
    </Identify>
  </xsl:template>


  <!-- template for ListMetadataFormats  -->
  <xsl:template match="Datasets" mode="ListMetadataFormats">
    <ListMetadataFormats>
      <metadataFormat>
        <metadataPrefix>
          <xsl:text>oai_dc</xsl:text>
        </metadataPrefix>
        <schema>
          <xsl:text>http://www.openarchives.org/OAI/2.0/oai_dc.xsd</xsl:text>
        </schema>
        <metadataNamespace>
          <xsl:text>http://www.openarchives.org/OAI/2.0/oai_dc/</xsl:text>
        </metadataNamespace>
      </metadataFormat>
      <metadataFormat>
        <metadataPrefix>
          <xsl:text>oai_datacite</xsl:text>
        </metadataPrefix>
        <schema>
          <xsl:text>http://schema.datacite.org/meta/kernel-4.3/metadata.xsd</xsl:text>
        </schema>
        <metadataNamespace>
          <xsl:text>http://datacite.org/schema/kernel-4</xsl:text>
        </metadataNamespace>
      </metadataFormat>
    </ListMetadataFormats>
  </xsl:template>

  <xsl:template match="Datasets" mode="ListSets">
    <ListSets>
      <xsl:apply-templates select="Rdr_Sets" />
    </ListSets>
  </xsl:template>

  <xsl:template match="Rdr_Sets">
    <set>
      <setSpec>
        <xsl:value-of select="@Type"/>
      </setSpec>
      <setName>
        <xsl:value-of select="@TypeName"/>
      </setName>
    </set>
  </xsl:template>

  <xsl:template match="Datasets" mode="ListIdentifiers">
    <xsl:if test="count(Rdr_Dataset) > 0">
      <ListIdentifiers>
        <xsl:apply-templates select="Rdr_Dataset" />
        <xsl:if test="$totalIds > 0">
          <resumptionToken>
            <xsl:attribute name="expirationDate">
              <xsl:value-of select="$dateDelete"/>
            </xsl:attribute>
            <xsl:attribute name="completeListSize">
              <xsl:value-of select="$totalIds"/>
            </xsl:attribute>
            <xsl:attribute name="cursor">
              <xsl:value-of select="$cursor"/>
            </xsl:attribute>
            <xsl:value-of select="$res"/>
          </resumptionToken>
        </xsl:if>
      </ListIdentifiers>
    </xsl:if>
  </xsl:template>

  <xsl:template match="Datasets" mode="ListRecords">
    <xsl:if test="count(Rdr_Dataset) > 0">
      <ListRecords>
        <xsl:apply-templates select="Rdr_Dataset" />
        <xsl:if test="$totalIds > 0">
          <resumptionToken>
            <xsl:attribute name="expirationDate">
              <xsl:value-of select="$dateDelete"/>
            </xsl:attribute>
            <xsl:attribute name="completeListSize">
              <xsl:value-of select="$totalIds"/>
            </xsl:attribute>
            <xsl:attribute name="cursor">
              <xsl:value-of select="$cursor"/>
            </xsl:attribute>
            <xsl:value-of select="$res"/>
          </resumptionToken>
        </xsl:if>
      </ListRecords>
    </xsl:if>
  </xsl:template>

  <xsl:template match="Datasets" mode="GetRecord">
    <GetRecord>
      <xsl:apply-templates select="Rdr_Dataset" />
    </GetRecord>
  </xsl:template>

  <xsl:template match="Rdr_Dataset">
    <xsl:choose>
      <xsl:when test="$oai_verb='ListIdentifiers'">
        <xsl:call-template name="Rdr_Dataset_Data"/>
      </xsl:when>
      <xsl:otherwise>
        <record>
          <xsl:call-template name="Rdr_Dataset_Data"/>
        </record>
      </xsl:otherwise>
    </xsl:choose>
  </xsl:template>

  <xsl:template name="Rdr_Dataset_Data">
    <header>
      <xsl:if test="@ServerState='deleted'">
        <xsl:attribute name="status">
          <xsl:text>deleted</xsl:text>
        </xsl:attribute>
      </xsl:if>
      <identifier>
        <xsl:text>oai:</xsl:text>
        <xsl:value-of select="$repIdentifier" />
        <xsl:text>:</xsl:text>
        <xsl:value-of select="@PublishId" />
      </identifier>
      <datestamp>
        <xsl:choose>
          <xsl:when test="ServerDateModified">
            <xsl:variable name="dateModified" select="concat(
            ServerDateModified/@Year, '-',
            format-number(ServerDateModified/@Month,'00'), '-',
            format-number(ServerDateModified/@Day,'00'), 'T',
            format-number(ServerDateModified/@Hour,'00'), ':',
            format-number(ServerDateModified/@Minute,'00'), ':',
            format-number(ServerDateModified/@Second,'00'), 'Z'
            )" />
            <xsl:value-of select="$dateModified" />
          </xsl:when>
          <xsl:otherwise>
            <xsl:variable name="datePublished" select="concat(
            ServerDatePublished/@Year, '-',
            format-number(ServerDatePublished/@Month,'00'), '-',
            format-number(ServerDatePublished/@Day,'00'), 'T',
            format-number(ServerDatePublished/@Hour,'00'), ':',
            format-number(ServerDatePublished/@Minute,'00'), ':',
            format-number(ServerDatePublished/@Second,'00'), 'Z'
            )" />
            <xsl:value-of select="$datePublished" />
          </xsl:otherwise>
        </xsl:choose>
      </datestamp>
      <!--<setSpec>
        <xsl:value-of select="SetSpec/@Value"/>
      </setSpec>-->
      <!--loop-->
      <xsl:apply-templates select="SetSpec" />
    </header>
    <xsl:choose>
      <!-- nicht bei ListIdentifiers und auch nicht bei gelöschten Datensätzen-->
      <xsl:when test="$oai_verb!='ListIdentifiers' and @ServerState!='deleted'">

        <metadata>
          <!-- <xsl:value-of select="$oai_metadataPrefix" /> -->
          <xsl:choose>
            <xsl:when test="$oai_metadataPrefix='oai_dc'">
              <xsl:apply-templates select="." mode="oai_dc" />
            </xsl:when>
            <xsl:when test="$oai_metadataPrefix='oai_datacite'">
              <xsl:apply-templates select="." mode="oai_datacite" />
            </xsl:when>
          </xsl:choose>
        </metadata>

      </xsl:when>
    </xsl:choose>
  </xsl:template>

  <xsl:template match="SetSpec">
    <setSpec>
      <xsl:value-of select="@Value"/>
    </setSpec>
  </xsl:template>

  <xsl:template match="Rdr_Dataset" mode="oai_dc">
    <oai_dc:dc xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/oai_dc/ http://www.openarchives.org/OAI/2.0/oai_dc.xsd">
      <!-- dc:title -->
      <xsl:apply-templates select="TitleMain" mode="oai_dc" />
      <!-- dc:title -->
      <xsl:apply-templates select="TitleAdditional" mode="oai_dc" />
      <!--<dc:creator>-->
      <!-- Creator: Autor (falls vorhanden), sonst Herausgeber (falls vorhanden), sonst Urhebende Koerperschaft  -->
      <xsl:choose>
        <xsl:when test="PersonAuthor">
          <xsl:apply-templates select="PersonAuthor" mode="oai_dc" />
        </xsl:when>
        <xsl:when test="@CreatingCorporation">
          <dc:creator>
            <xsl:value-of select="@CreatingCorporation" />
          </dc:creator>
        </xsl:when>
      </xsl:choose>
      <!-- dc:subject -->
      <xsl:apply-templates select="Subject" mode="oai_dc" />
      <!-- dc:description -->
      <xsl:apply-templates select="TitleAbstract" mode="oai_dc" />
      <!-- dc:description -->
      <xsl:apply-templates select="TitleAbstractAdditional" mode="oai_dc" />
      <!-- dc:publisher -->
      <dc:publisher>
        <!-- <xsl:value-of select="@PublisherName" /> -->
        <xsl:value-of select="@CreatingCorporation" />
      </dc:publisher>

      <!-- dc:contributor -->
      <xsl:apply-templates select="PersonContributor" mode="oai_dc" />
      <!-- dc:date (call-template, weil die 'Funktion' nur einmal aufgerufen werden soll, nicht einmal für jedes Date-->
      <xsl:call-template name="RdrDate"/>
      <!-- dc:date: embargo date -->
      <xsl:apply-templates select="EmbargoDate" mode="oai_dc" />
      <!-- dc:type -->
      <!-- <xsl:apply-templates select="@Type" mode="oai_dc" /> -->
      <dc:type>Dataset</dc:type>
      <!-- dc:format -->
      <xsl:apply-templates select="File/@MimeType" mode="oai_dc" />
      <!-- <dc:format> -->
      <xsl:apply-templates select="File" mode="oai_dc" />
      <!-- dc:identifier -->
      <!-- <xsl:if test="Identifier">
       <xsl:apply-templates select="Identifier" mode="oai_dc" />
       </xsl:if> -->
      <dc:identifier>
        <xsl:value-of select="@landingpage"/>
      </dc:identifier>
      <!-- dc:language -->
      <xsl:apply-templates select="@Language" mode="oai_dc" />
      <!-- dc:relation -->
      <xsl:if test="Identifier">
        <xsl:apply-templates select="Identifier" mode="oai_dc" />
      </xsl:if>
      <xsl:apply-templates select="Reference" mode="oai_dc" />
      <!-- dc:coverage -->
      <xsl:apply-templates select="Coverage" mode="oai_dc" />
      <!-- dc:rights -->
      <xsl:apply-templates select="Licence" mode="oai_dc" />
      <xsl:if test="EmbargoDate and ($unixTimestamp &lt; EmbargoDate/@UnixTimestamp)">
        <dc:rights>embargo</dc:rights>
      </xsl:if>
    </oai_dc:dc>
  </xsl:template>

  <xsl:template match="Coverage" mode="oai_dc">
    <dc:coverage>
      <xsl:variable name="geolocation" select="concat(
      'SOUTH-BOUND LATITUDE: ', @YMin,
      ' * WEST-BOUND LONGITUDE: ', @XMin,
      ' * NORTH-BOUND LATITUDE: ', @YMax,
      ' * EAST-BOUND LONGITUDE: ', @XMax
      )" />
      <xsl:value-of select="$geolocation" />

      <!-- <xsl:text>&#xA;</xsl:text> -->
      <xsl:if test="@ElevationMin != '' and @ElevationMax != ''">
        <xsl:value-of select="concat(' * ELEVATION MIN: ', @ElevationMin, ' * ELEVATION MAX: ', @ElevationMax)" />
      </xsl:if>
      <xsl:if test="@ElevationAbsolut != ''">
        <xsl:value-of select="concat(' * ELEVATION ABSOLUT: ', @ElevationAbsolut)" />
      </xsl:if>

      <!-- <xsl:text>&#xA;</xsl:text> -->
      <xsl:if test="@DepthMin != '' and @DepthMax != ''">
        <xsl:value-of select="concat(' * DEPTH MIN: ', @DepthMin, ' * DEPTH MAX: ', @DepthMax)" />
      </xsl:if>
      <xsl:if test="@DepthAbsolut != ''">
        <xsl:value-of select="concat(' * DEPTH ABSOLUT: ', @DepthAbsolut)" />
      </xsl:if>

      <!-- <xsl:text>&#xA;</xsl:text> -->
      <xsl:if test="@TimeMin != '' and @TimeMax != ''">
        <xsl:value-of select="concat(' * TIME MIN: ', @TimeMin, ' * TIME MAX: ', @TimeMax)" />
      </xsl:if>
      <xsl:if test="@TimeAbsolut != ''">
        <xsl:value-of select="concat(' * TIME ABSOLUT: ', @TimeAbsolut)" />
      </xsl:if>

    </dc:coverage>
  </xsl:template>

  <xsl:template match="TitleMain" mode="oai_dc">
    <dc:title>
      <xsl:attribute name="xml:lang">
        <xsl:value-of select="@Language" />
      </xsl:attribute>
      <xsl:value-of select="@Value"/>
    </dc:title>
  </xsl:template>

  <xsl:template match="TitleAdditional" mode="oai_dc">
    <dc:title>
      <xsl:attribute name="xml:lang">
        <xsl:value-of select="@Language" />
      </xsl:attribute>
      <xsl:value-of select="@Value"/>
    </dc:title>
  </xsl:template>

  <xsl:template match="TitleAbstract" mode="oai_dc">
    <dc:description>
      <xsl:attribute name="xml:lang">
        <xsl:value-of select="@Language" />
      </xsl:attribute>
      <xsl:value-of select="@Value" />
    </dc:description>
  </xsl:template>
  <xsl:template match="TitleAbstractAdditional" mode="oai_dc">
    <dc:description>
      <xsl:attribute name="xml:lang">
        <xsl:value-of select="@Language" />
      </xsl:attribute>
      <xsl:value-of select="@Value" />
    </dc:description>
  </xsl:template>

  <xsl:template match="Subject" mode="oai_dc">
    <dc:subject>
      <xsl:if test="@Language != ''">
        <xsl:attribute name="xml:lang">
          <xsl:value-of select="@Language" />
        </xsl:attribute>
      </xsl:if>
      <xsl:value-of select="@Value" />
    </dc:subject>
  </xsl:template>

  <xsl:template match="Reference" mode="oai_dc">
    <dc:relation>
      <xsl:value-of select="@Value" />
    </dc:relation>
  </xsl:template>



  <xsl:template match="PersonAuthor|PersonEditor" mode="oai_dc">
    <dc:creator>
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
    </dc:creator>
  </xsl:template>

  <xsl:template match="PersonContributor" mode="oai_dc">
    <dc:contributor>
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
    </dc:contributor>
  </xsl:template>

  <xsl:template name="RdrDate">
    <dc:date>
      <xsl:choose>
        <xsl:when test="PublishedDate">
          <xsl:variable name="publishedDate" select="concat(
                    PublishedDate/@Year, '-',
                    format-number(PublishedDate/@Month,'00'), '-',
                    format-number(PublishedDate/@Day,'00')        
                )" />
          <xsl:value-of select="$publishedDate" />
        </xsl:when>
        <xsl:otherwise>
          <xsl:variable name="serverDatePublished" select="concat(
                    ServerDatePublished/@Year, '-',
                    format-number(ServerDatePublished/@Month,'00'), '-',
                    format-number(ServerDatePublished/@Day,'00')        
                )" />
          <xsl:value-of select="$serverDatePublished" />
        </xsl:otherwise>
      </xsl:choose>
    </dc:date>
  </xsl:template>

  <xsl:template match="EmbargoDate" mode="oai_dc">
    <xsl:if test="$unixTimestamp &lt; ./@UnixTimestamp">
      <dc:date>
        <xsl:text>info:eu-repo/date/embargoEnd/</xsl:text>
        <xsl:value-of select="./@Year"/>
-        <xsl:value-of select="format-number(./@Month,'00')"/>
-        <xsl:value-of select="format-number(./@Day,'00')"/>
      </dc:date>
    </xsl:if>
  </xsl:template>


  <!-- für ListRecords -->
  <!-- <xsl:template match="@Type" mode="oai_dc">
    <dc:type>
      <xsl:value-of select="." />
    </dc:type>
    <dc:type>
      <xsl:text>data-type:</xsl:text>
      <xsl:value-of select="." />
    </dc:type>
  </xsl:template> -->

  <xsl:template match="File/@MimeType" mode="oai_dc">
    <dc:format>
      <xsl:choose>
        <xsl:when test=". = 'application/x-sqlite3'">
          <xsl:text>application/geopackage+sqlite3</xsl:text>
        </xsl:when>
        <xsl:otherwise>
          <xsl:value-of select="." />
        </xsl:otherwise>
      </xsl:choose>
    </dc:format>
  </xsl:template>

  <!-- <xsl:template match="File" mode="oai_dc">
    <dc:identifier>
     <xsl:value-of select="@PathName" />        
      <xsl:value-of select="concat($downloadLink, @Id)" />
    </dc:identifier>
  </xsl:template> -->

  <xsl:template match="Identifier" mode="oai_dc">
    <dc:relation>
      <!-- <xsl:value-of select="concat($doiLink, @Value)" /> -->
      <xsl:value-of select="concat($doiPrefix, @Value)" />
    </dc:relation>
  </xsl:template>

  <xsl:template match="@CreatingCorporation" mode="oai_dc">
    <dc:language>
      <xsl:value-of select="." />
    </dc:language>
  </xsl:template>

  <xsl:template match="@Language" mode="oai_dc">
    <dc:language>
      <xsl:value-of select="." />
    </dc:language>
  </xsl:template>

  <xsl:template match="Licence" mode="oai_dc">
    <dc:rights>
      <xsl:value-of select="@NameLong" />
    </dc:rights>
    <xsl:if test="@Name = 'CC-BY-4.0' or @Name = 'CC-BY-SA-4.0'">
      <dc:rights>
        <xsl:text>info:eu-repo/semantics/openAccess</xsl:text>
      </dc:rights>
    </xsl:if>
  </xsl:template>

</xsl:stylesheet>