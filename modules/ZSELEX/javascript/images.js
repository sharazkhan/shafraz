function calc()

{
    //alert("Hello");
    var AW = document.getElementById('Dynimage').clientWidth;
    var AH = document.getElementById('Dynimage').clientHeight;
    var root = document.getElementById('Dynimage').src;
    var H;
    var W;



    //alert("Actual Height: "+AH+" Actual Width"+AW+" image root="+root);

    if (AH < 210 && AW < 170)
    {
        //alert("Test1");
        return;
    }

    if (AH > 210 && AW < 170)
    {
        //alert("Test2");
        H = 210;
        W = AW * ((210 * 100) / AH) / 100;
        document.getElementById('imagesec').innerHTML = "<img src=" + root + " id='Dynimage' width='" + W + "' height='" + H + "'/>";

        return;
    }

    if (AH < 210 && AW > 170)
    {
        //alert("Test");
        //alert("Test3");
        W = 170;
        H = AH * ((170 * 100) / AW) / 100;
        document.getElementById('imagesec').innerHTML = "<img src=" + root + " id='Dynimage' width='" + W + "' height='" + H + "'/>";
        //alert("Actual Height: "+H+" Actual Width  "+W);

        return;
    }

    if (AH > 210 && AW > 170)
    {

        H = 210;
        W = AW * ((210 * 100) / AH) / 100;

        var WTmp = W;
        if (W > 170)
        {
            W = 170;
            H = H * ((170 * 100) / WTmp) / 100;
        }
        //alert("Test4");
        document.getElementById('imagesec').innerHTML = "<img src=" + root + " id='Dynimage' width='" + W + "' height='" + H + "'/>";
        //alert("Height and Width is abnormal");
        //alert("Actual Height: "+H+" Actual Width  "+W);
        return;
    }



}