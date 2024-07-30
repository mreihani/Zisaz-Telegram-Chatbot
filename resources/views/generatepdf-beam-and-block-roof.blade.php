<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            فایل محاسبات
        </title>
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

        <style>
           .vertical-center {
                height:100%;
                width:100%;
                text-align: center;  /* align the inline(-block) elements horizontally */
                font: 0/0 a;         /* remove the gap between inline(-block) elements */
            }
            .vertical-center:before {    /* create a full-height inline block pseudo=element */
                content: " ";
                display: inline-block;
                vertical-align: middle;    /* vertical alignment of the inline element */
                height: 100%;
            }
            .vertical-center > .container {
                max-width: 100%;
                display: inline-block;
                vertical-align: middle;  /* vertical alignment of the inline element */
            }

            body, th, td, tr, div, span { 
                font-family: DejaVu Sans, serif;
                direction: rtl;  
            }

            /* @font-face {
                font-family: 'iransansdn'; 
                src: url('{{asset('assets/fonts/iransansdn.ttf')}}') format('truetype'); 
            }
            body, th, td, tr, div, span { font-family: 'iransansdn'; } */
        </style>
    </head>
    <body>
        <div class="jumbotron vertical-center">
            <div class="container">
                <div class="row">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>شرح</th>
                                <th>تعداد یا مقدار</th>
                                <th>واحد اندازه گیری</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    مساحت کل سقف
                                </td>
                                <td>
                                    {{$a}}
                                </td>
                                <td>
                                    متر مربع
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    ارتفاع تیرچه
                                </td>
                                <td>
                                    {{$h}}
                                </td>
                                <td>
                                    سانتی متر
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    تعداد فوم مورد نیاز 
                                </td>
                                <td>
                                    {{$n}}
                                </td>
                                <td>
                                    عدد
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    متراژ تیرچه مورد نیاز تقریبی
                                </td>
                                <td>
                                    {{$l}}
                                </td>
                                <td>
                                    متر
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    حجم بتون تقریبی
                                </td>
                                <td>
                                    {{$v}}
                                </td>
                                <td>
                                    متر مکعب
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    وزن  سیمان  تقریبی مورد نیا ز
                                </td>
                                <td>
                                    {{$w}}
                                </td>
                                <td>
                                    کیلوگرم
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    وزن شن و ماسه  تقریبی مورد نیاز 
                                </td>
                                <td>
                                    {{$s}}
                                </td>
                                <td>
                                    کیلوگرم
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    وزن میلگرد حراراتی تقریبی مورد نیاز 
                                </td>
                                <td>
                                    {{$wi}}
                                </td>
                                <td>
                                    کیلوگرم
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>