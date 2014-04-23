{include file="ui/Index/tpls/header.tpl"}
  <title>{$PROGRAM_DETAILS.title}</title>
  <script type="text/javascript" src="/library/syntaxhighlighter/scripts/shCore.js"></script> 
  <script type="text/javascript" src="/library/syntaxhighlighter/scripts/shBrush{$LANGUAGE}.js"></script>
  <script type="text/javascript">SyntaxHighlighter.all();</script>
  
  <link type="text/css" rel="stylesheet" href="/library/syntaxhighlighter/styles/shCoreCustom.css"/>
  <link type="text/css" rel="stylesheet" href="/ui/Explorer/css/explorer.css"/>
  
<div class="body-main"
    style="background: #F9F9F9; border: 1px solid #AAA;">
    <div class="program-header-div">
        <br>
        <div class="program-title-div">
            <b>{$PROGRAM_DETAILS.title}</b>
            {if $PROGRAM_DETAILS.is_verified eq '1'}
                <img class="verified-image" src="/ui/Explorer/imgs/verified.png">
            {/if}
            <span class="top-side-buttons file-download-link"><a target="_blank" href="/download/{$PROGRAM_DETAILS.id}">Download</a></span>
            <span class="top-side-links">
                <a href="/{$PROGRAM_DETAILS.fk_language}">{$PROGRAM_DETAILS.language_name}</a> / 
                <a target="_blank" href="/{$PROGRAM_DETAILS.fk_language}/{$PROGRAM_DETAILS.fk_category}">{$PROGRAM_DETAILS.category_name}</a>
            </span>
       </div><br>
       <small>{$PROGRAM_DETAILS.description}</small>
        <div class="program-stats">
            <span style="font-size:13px;float:right"> 
                Size:{$SOURCE_STATS.fileSize} KBs |
                Chars:{$SOURCE_STATS.charCount} |
                Words:{$SOURCE_STATS.wordCount} |
                Lines:{$SOURCE_STATS.lineCount} 
            </span>
        </div>
     </div>
   <pre class="brush:{$LANGUAGE|strtolower};gutter:true;toolbar:false;">{$SOURCE_CODE}</pre>
</div>

{include file="ui/Index/tpls/footer.tpl"}