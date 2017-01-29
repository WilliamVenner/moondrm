<script>

    $(window).on("load",function() {
        
        var i = 0;
        $(".code").each(function() {
        
            $(this).attr("id","ace-editor-" + i);
            
            var editor = ace.edit("ace-editor-" + i);
            editor.setTheme("ace/theme/monokai");
            editor.getSession().setMode("ace/mode/lua");
            editor.setShowPrintMargin(false);
            if ($(this).data("read-only") == "y") {
                editor.setReadOnly(true);
            }
            
            i = i + 1;
        
        });
        
    });
    
</script>