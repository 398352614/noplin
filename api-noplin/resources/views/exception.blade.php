@component('mail::message')
    # 报错
    ## {{$time}}
    ## {{config('app.url')}}
    {{$exception}}
@endcomponent