<!-- Load Pako ZLIB library to enable PDF compression -->
{{--<script src="https://kendo.cdn.telerik.com/2019.3.1023/js/pako_deflate.min.js"></script>--}}

<script type="x/kendo-template" id="page-template">
    <div class="page-template">
        <div class="header">
            <div style="float: right">Page #: pageNum # of #: totalPages #</div>
            @yield('titule')
        </div>
        <div class="watermark">Healthy Pets</div>
        <div class="footer">
            Page #: pageNum # of #: totalPages #
        </div>
    </div>
</script>

<style>
    /* Page Template for the exported PDF */
    .page-template {
        font-family: "DejaVu Sans", "Arial", sans-serif;
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
    }
    .page-template .header {
        position: absolute;
        top: 30px;
        left: 30px;
        right: 30px;
        border-bottom: 1px solid #888;
        color: #888;
    }
    .page-template .footer {
        position: absolute;
        bottom: 30px;
        left: 30px;
        right: 30px;
        border-top: 1px solid #888;
        text-align: center;
        color: #888;
    }
    .page-template .watermark {
        font-weight: bold;
        font-size: 400%;
        text-align: center;
        margin-top: 30%;
        color: #aaaaaa;
        opacity: 0.1;
        transform: rotate(-35deg) scale(1.7, 1.5);
    }

    /* Content styling */
    .customer-photo {
        display: inline-block;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background-size: 32px 35px;
        background-position: center center;
        vertical-align: middle;
        line-height: 32px;
        box-shadow: inset 0 0 1px #999, inset 0 0 10px rgba(0,0,0,.2);
        margin-left: 5px;
    }
    kendo-pdf-document .customer-photo {
        border: 1px solid #dedede;
    }
    .customer-name {
        display: inline-block;
        vertical-align: middle;
        line-height: 32px;
        padding-left: 3px;
    }
</style>