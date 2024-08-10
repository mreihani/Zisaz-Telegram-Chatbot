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
                    جدول محاسبات دیوار پارتیشن بلوکی آپارتمان
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
                                    ابعاد بلوک
                                </td>
                                <td>
                                    {{$b}} * 20 * 40
                                </td>
                                <td>
                                    عدد
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    متراژ کل دیوار چینی
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
                                    تعداد بلوک مورد نیاز
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
                                    مقدار سیمان مورد نیاز
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
                                    مقدار ماسه مورد نیاز
                                </td>
                                <td>
                                    {{$s}}
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
                    1- این محاسبات برای بلوک چینی پارتیشن آپارتمان انجام شده است.
                </div>
                <div class="row">
                    2- عیار ملات بلوک چینی 250 کیلو گرم بر مترمکعب در نظر گرفته شده است.
                </div>
                <div class="row">
                    3- پرت مصالح 6% در نظر گرفته شده است.
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>