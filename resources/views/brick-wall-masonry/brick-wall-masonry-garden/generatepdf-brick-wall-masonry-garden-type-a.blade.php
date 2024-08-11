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
                                    عمق شالوده
                                </td>
                                <td>
                                    {{$d}}
                                </td>
                                <td>
                                    متر
                                </td>
                            </tr>
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
                    1- این محاسبات برای دیوار چینی  با جرز های بلوکی بدون شناژ با فاصله 3.60 متر  انجام شده است.
                </div>
                <div class="row">
                    2- ابعاد بلوک 20*20*40 سانتی متر در نظر گرفته شده است.
                </div>
                <div class="row">
                    3- برای جلوگیری از نشست دیوار ، از کف شالوده تا همسطح  زمین بلوک 40 سانتی چیده می شود.
                </div>
                <div class="row">
                    4- عیار ملات بلوک چینی  250 کیلو گرم بر مترمکعب در نظر گرفته شده است.
                </div>
                <div class="row">
                    5- پرت مصالح 6% در نظر گرفته شده است.
                </div>
            </div>
        </div>

        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>