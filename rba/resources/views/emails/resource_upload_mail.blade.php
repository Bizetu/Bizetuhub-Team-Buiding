@extends('layouts.email')

@section('content')


<p style="font-family:poppins, sans-serif;font-size: 28px;line-height:1.6;font-weight:normal;margin:0 0 30px;padding:0;color:#F18A21;text-align:center;">Resource Upload</p>
<hr class="line-footer">
<p class="bigger-bold" style="font-family: poppins, sans-serif;font-size: 18px;line-height: 1.6;font-weight: normal;margin: 30px 0 30px;padding: 0;color:#404040;text-align:center;">Hello {{ ucwords(strtolower($name)) ?: '' }},</p>

<p style="font-family: poppins, sans-serif;font-size: 18px;line-height: 1.6;font-weight: normal;margin: 30px 0 30px;padding: 0;color:#404040;text-align:center;">
    A resource on {{$topic }} has been uploaded to {{ config('app.name', 'The Challenging Patriarchy Program') }} platform by {{$uploader}}.</p>

  <p style="font-family: poppins, sans-serif;font-size: 18px;line-height: 1.6;font-weight: normal;margin: 30px 0 30px;padding: 0;color:#404040;text-align:center;">
      In order to assess the resource please login to the platform by clicking on the link below .</p>
  
      <p style="font-family: poppins, sans-serif;margin: 30px 0 30px; text-align:center;color:#23245F;font-size:16px;font-weight: bold;">
        <a href="{{$url}}" class="btn-drk-left" style=" background: #F87522;
        padding: 10px 15px;
        color: #fff;
        font-size: 18px;
        text-decoration: none;"> Login
        </a>
      </p>


<p style="font-family: poppins, sans-serif;font-size: 18px;line-height: 1.6;font-weight: bold;margin: 20px 0 0;padding: 0;color:#404040;text-align:center;">
Best Regards,
</p>
<p style="font-family:poppins, sans-serif;font-size: 18px;line-height: 1.6;font-weight: bold;margin: 0 0 10px;padding: 0;color:#404040;text-align:center;">
  The Challenging Patriarchy Program
</p>

<p style="font-family:poppins, sans-serif;font-size: 18px;line-height: 1.6;font-weight: bold;margin: 0 0 10px;padding: 0;color:#662F8E;text-align:center;">
 support@farwell-consultants.com
</p>


@endsection
