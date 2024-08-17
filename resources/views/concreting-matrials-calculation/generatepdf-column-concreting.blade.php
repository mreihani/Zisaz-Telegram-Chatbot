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
                    محاسبه مصالح لازم برای بتن ریزی ستون ها
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
                                    حجم کل بتن ریزی
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
                                    وزن سیمان مصرفی
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
                                    وزن ماسه شسته
                                </td>
                                <td>
                                    {{$w2}}
                                </td>
                                <td>
                                    کیلوگرم
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    وزن شن نخودی و بادامی
                                </td>
                                <td>
                                    {{$w3}}
                                </td>
                                <td>
                                    کیلوگرم
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    حجم آب
                                </td>
                                <td>
                                    {{$v1}}
                                </td>
                                <td>
                                    لیتر
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row mt-5">
                    ⚠ توجه
                </div>
                <div class="row">
                    محاسبات فوق بر اساس شرایط معمول کارگاهی بود و برای محاسبات دقیق بایستی به طرح اختلاط بتون بر اساس مصالح موجود در محل مراجعه کرد.
                </div>

            </div>
        </div>

        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>