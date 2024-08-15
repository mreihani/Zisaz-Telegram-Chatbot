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
                    محاسبه معادل سازی میلگرد
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
                                    سطح مقطع کل {{$n}} عدد میلگرد {{$d1}}
                                </td>
                                <td>
                                    {{$a1}}
                                </td>
                                <td>
                                    سانتی متر مربع
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    سطح مقطع یک عدد میلگرد جدید با قطر {{$d2}}
                                </td>
                                <td>
                                    {{$a2}}
                                </td>
                                <td>
                                    سانتی متر مربع
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    تعداد میلگرد معادل سازی شده
                                </td>
                                <td>
                                    {{$n1}}
                                </td>
                                <td>
                                    عدد
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