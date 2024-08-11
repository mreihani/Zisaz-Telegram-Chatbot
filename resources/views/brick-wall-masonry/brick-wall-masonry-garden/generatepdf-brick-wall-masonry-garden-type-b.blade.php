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
                    جدول محاسبات دیوار بلوکی حصار باغ یا حیاط
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
                                    تعداد بلوک
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
                                    تعداد شناژ های عمودی
                                </td>
                                <td>
                                    {{$nc}}
                                </td>
                                <td>
                                    عدد
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    حجم ملات دیوار چینی
                                </td>
                                <td>
                                    {{$v1}}
                                </td>
                                <td>
                                    متر مکعب
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    حجم بتن شناژ ها
                                </td>
                                <td>
                                    {{$v2}}
                                </td>
                                <td>
                                    متر مکعب
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    وزن سیمان
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
                                    وزن ماسه
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
                                    طول پی کنی
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
                                    وزن میلگرد 14
                                </td>
                                <td>
                                    {{$w1}}
                                </td>
                                <td>
                                    کیلوگرم
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    تعداد خاموت
                                </td>
                                <td>
                                    {{$nk}}
                                </td>
                                <td>
                                    عدد
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    وزن خاموت 8
                                </td>
                                <td>
                                    {{$w2}}
                                </td>
                                <td>
                                    کیلوگرم
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    1- این محاسبات برای دیوار چینی با شناژ افقی و شناژ عمودی به فاصله 3.60 متر  انجام شده است.
                </div>
                <div class="row">
                    2- ابعاد بلوک 20*20*40 سانتی متر در نظر گرفته شده است.
                </div>
                <div class="row">
                    3- قطر میلگرد شناژ 14 میلیمتر در نظر گرفته شده است.
                </div>
                <div class="row">
                    4- قطر میلگرد خاموت 8 میلیمتر در نظر گرفته شده است.
                </div>
                <div class="row">
                    5- عیار ملات بلوک چینی 250 کیلو گرم بر مترمکعب در نظر گرفته شده است.
                </div>
                <div class="row">
                    6- پرت مصالح 6% در نظر گرفته شده است.
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>