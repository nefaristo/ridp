
    <div style="height:85vh;display:flex;flex-direction:column ;flex-wrap:nowrap;justify-content:flex-start;">
        <div id="sql" style="display:none">
            {!!$content["sql"]!!}
        </div>
        <div id="html" style="height:10vh;display:none">
            {!!$content["html"]!!}
        </div>
        <div id="chartRender" style="overflow:visible;height:100%;"></div>
        {!!\Lava::render($content["chart"]["type"],$content["id"],"chartRender");!!}
    </div>
