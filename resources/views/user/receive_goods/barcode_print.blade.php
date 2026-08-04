<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Barcode Print</title>
    <style>html,body,iframe{width:100%;height:100%;margin:0;border:0;overflow:hidden}</style>
</head>
<body>
    <iframe id="barcode-pdf" src="{{ $pdfUrl }}#toolbar=0"></iframe>
    <script>
        document.getElementById('barcode-pdf').addEventListener('load', function () {
            window.setTimeout(() => {
                this.contentWindow.focus();
                this.contentWindow.print();
            }, 350);
        });
    </script>
</body>
</html>
