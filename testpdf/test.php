<?php
if (isset($_POST['submit'])) {

    $pdfDirectory = "pdf/";
    $thumbDirectory = "pdfimage/";

//get the name of the file
    $filename = basename($_FILES['pdf']['name'], ".pdf");

//remove all characters from the file name other than letters, numbers, hyphens and underscores
    $filename = preg_replace("/[^A-Za-z0-9_-]/", "", $filename) . ".pdf";

//name the thumbnail image the same as the pdf file
    $thumb = basename($filename, ".pdf");

    if (move_uploaded_file($_FILES['pdf']['tmp_name'], $pdfDirectory . $filename)) {

        //the path to the PDF file
        $pdfWithPath = $pdfDirectory . $filename;

        //add the desired extension to the thumbnail
        $thumb = $thumb . ".jpg";

        //execute imageMagick's 'convert', setting the color space to RGB and size to 200px wide
        //exec("convert \"{$pdfWithPath}[0]\" -colorspace RGB -geometry 200 $thumbDirectory$thumb");

        //exec("convert " . $pdfWithPath[0] . " -colorspace RGB -geometry 200 $thumbDirectory$thumb");

        exec("convert $pdfWithPath[0] output.jpg");
        //show the image
        echo "<p><a href=\"$pdfWithPath\"><img src=\"pdfimage/$thumb\" alt=\"\" /></a></p>";
    }
}
?>

<form method="post" action="" enctype="multipart/form-data">
    <input type="file" name="pdf" />
    <input type="submit" name="submit" value="Upload" />
</form>