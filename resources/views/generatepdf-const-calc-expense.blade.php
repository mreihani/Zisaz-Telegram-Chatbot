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
                    جدول محاسبات کل  زیر بنا و هزینه ساخت
                </h1>

                <div class="row">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>شرح</th>
                                <th>مقدار / متراژ</th>
                                <th>واحد اندازه گیری</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    مساحت زمین
                                </td>
                                <td>
                                    {{$initialParameters['a']}}
                                </td>
                                <td>
                                    متر مربع
                                </td>
                            </tr>
                          
                            @if($initialParameters['nb'] > 0)
                                @for($i = 0; $i < $initialParameters['nb']; $i++)
                                    <tr>
                                        <td>
                                            زیر بنای زیر زمین {{ $i + 1 }}
                                        </td>
                                        <td>
                                            {{ number_format($totalAreaASK['abk' . ($i + 1)]) }}
                                        </td>
                                        <td>
                                            متر مربع
                                        </td>
                                    </tr>
                                @endfor
                            @endif

                            <tr>
                                <td>
                                    زیر بنای طبقه همکف به همراه بالکن
                                </td>
                                <td>
                                    {{ number_format($totalAreaASK['agk']) }}
                                </td>
                                <td>
                                    متر مربع
                                </td>
                            </tr>
                            
                            @if($initialParameters['nf'] > 0)
                                @for($i = 0; $i < $initialParameters['nf']; $i++)
                                    <tr>
                                        <td>
                                            زیر بنای طبقه {{ $i + 1 }} به همراه بالکن
                                        </td>
                                        <td>
                                            {{ number_format($totalAreaASK['afk' . ($i + 1)]) }}
                                        </td>
                                        <td>
                                            متر مربع
                                        </td>
                                    </tr>
                                @endfor
                            @endif

                            <tr>
                                <td>
                                    زیر بنای سر پله
                                </td>
                                <td>
                                    {{ number_format($area['as']) }}
                                </td>
                                <td>
                                    متر مربع
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    زیر بنای قابل ساخت
                                </td>
                                <td>
                                    {{ number_format($totalAreaASK['ask']) }}
                                </td>
                                <td>
                                    متر مربع
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    هزینه ساخت (هر متر مربع)
                                </td>
                                <td>
                                    {{ number_format($initialParameters['pc']) }}
                                </td>
                                <td>
                                    تومان
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    قیمت هر متر مربع زمین (ملک)
                                </td>
                                <td>
                                    {{ number_format($initialParameters['pm']) }}
                                </td>
                                <td>
                                    تومان
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    قیمت فروش آپارتمان (هر متر مربع)
                                </td>
                                <td>
                                    {{ number_format($initialParameters['pa']) }}
                                </td>
                                <td>
                                    تومان
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    هزینه های پروانه ساخت شهرداری
                                </td>
                                <td>
                                    {{ number_format($initialParameters['ps']) }}
                                </td>
                                <td>
                                    تومان
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    هزینه های خاص این پروژه
                                </td>
                                <td>
                                    {{ number_format($initialParameters['pk']) }}
                                </td>
                                <td>
                                    تومان
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    جمع کل هزینه ساخت
                                </td>
                                <td>
                                    {{ number_format($constExpenses['ack']) }}
                                </td>
                                <td>
                                    تومان
                                </td>
                            </tr>
                            
                            <tr>
                                <td>
                                    جمع کل قیمت زمین
                                </td>
                                <td>
                                    {{ number_format($constExpenses['pmk']) }}
                                </td>
                                <td>
                                    تومان
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    جمع کل سرمایه گذاری (هزینه ساخت + قیمت زمین)
                                </td>
                                <td>
                                    {{ number_format($constExpenses['zsk']) }}
                                </td>
                                <td>
                                    تومان
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    کل متراژ مفید قابل فروش (کل متراژ سند های نهایی)
                                </td>
                                <td>
                                    {{ number_format($totalAreaAPK['apk']) }}
                                </td>
                                <td>
                                    تومان
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <div class="row mt-5">
                    ⚠ توجه
                </div>
                <div class="row">
                    1- محاسبات فوق تقریبی می باشد و صرفا برای برآورد های اولیه و تقریبی مناسب است و برای تصمیمات دقیق قابل استناد نمی باشد.
                </div>
                <div class="row">
                    2- برای محاسبات و برآورد های دقیق لازم است با توجه به موقعیت، ابعاد، شرایط و ضوابط خاص هر ملک و همچنین پس از تهیه نقشه های معماری نسبت به محاسبات دقیق اقدام نموده و تصمیمات  قابل استناد اتخاذ گردد.    
                </div>
                <div class="row">
                    3- مسئولیت هرگونه تصمیم و قرارداد به عهده تصمیم گیران و طرفین قرارداد می باشد و سامانه زی ساز هیچگونه مسئولیتی در قبال محاسبات تقریبی فوق و همچنین تصمیمات طرفین قرارداد ندارد.
                </div>
             
            </div>
        </div>

        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>