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
                    مصالح مورد نیاز کاشی بدنه 
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
                                    متراژ کل کار
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
                                    وزن سیمان مورد نیاز
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
                                    وزن ماسه مورد نیاز
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

                <div class="row mt-5">
                    ⚠ توجه
                </div>
                <div class="row">
                    1- این محاسبات بر اساس تجربه کارگاهی انجام شده است.
                </div>
                <div class="row">
                    2- در ورود اطلاعات خواسته شده دقت کنید.
                </div>
                <div class="row">
                    3- در صورتی که اطلاعات دقیق وارد نشود از اعداد پیش فرض  سیستم استفاده می شود.
                </div>
                <div class="row">
                    4- پرت مصالح 5% در نظر گرفته شده است.
                </div>

            </div>
        </div>

        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>