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

                <h1 class="mb-3 pb-3 text-center">
                    جدول محاسبات سقف تیرچه و بلوک
                </h1>

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

                <div class="row mt-5">
                    ⚠ توجه
                </div>
                <div class="row">
                    1-اندازه و مقادیر دقیق پارامتر های خروجی تابع ابعاد شناژ ها، پوتر های بتونی ، همچنین اندازه  دهانه تیرچه ها می باشد 
                </div>
                <div class="row">
                    2-ارتفاع تیرچه  H سانتی متر 
                </div>
                <div class="row">
                    3-ابعاد فوم 200*50 سانتی متر در نظر گرفته شده است .
                </div>
                <div class="row">
                    4- عیار بتون 350 کیلو گرم بر مترمکعب د رنظر گرفته شده است .
                </div>
             
            </div>
        </div>

        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>