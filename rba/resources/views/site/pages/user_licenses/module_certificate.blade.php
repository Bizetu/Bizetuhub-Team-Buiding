<style>
    @font-face {
        font-family: 'Aptos';
        src: url("fonts/Aptos.ttf") format('truetype');
    }

    @font-face {
        font-family: 'Aptos-Black';
        src: url("fonts/Aptos-Black.ttf") format('truetype');
    }

    @font-face {
        font-family: 'Aptos-Bold';
        src: url("fonts/Aptos-Bold.ttf") format('truetype');
    }

    @font-face {
        font-family: 'Aptos-Light';
        src: url("fonts/Aptos-Light.ttf") format('truetype');
    }

    @font-face {
        font-family: 'Aptos-Mono';
        src: url("fonts/Aptos-Mono.ttf") format('truetype');
    }

    @font-face {
        font-family: 'Aptos-Regular';
        src: url("fonts/Aptos.ttf") format('truetype');
    }



    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .page {
        position: relative;
        width: 297mm;
        height: 210mm;
        overflow: hidden;
        font-family: 'Aptos';
    }

    .background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    .content {
        top: 12.6em;
        position: relative;
        text-align: center;
        width: 100%;
        height: 100%;
    }

    .title {
        position: absolute;
        width: 100%;
        text-align: center;
        font-size: 28px;
        font-family: 'Aptos-Bold';
        color: #1F3B67;
    }

    .congratulations {
        position: absolute;
        top: 17em;
        width: 100%;
        text-align: center;
        font-family: 'Aptos-Bold';
        font-size: 24px;

    }

    .name {
        position: absolute;
        top: 19.5em;
        width: 100%;
        text-align: center;
        font-size: 32px;
        color: #1F3B67;
        font-family: 'Aptos-Bold';
    }

    .body {
        position: absolute;
        top: 23.5em;
        width: 100%;
        text-align: center;
        font-size: 20px;
        font-family: 'Aptos-Regular';
    }
    .jobrole{
        font-family: 'Aptos-Bold';
    }
    .date {
        position: absolute;
        top: 35.5em;
        left:18em;
        width: 100%;
        text-align: left;
        font-size: 14px;
    }

    .signature {
        position: absolute;
        top: 34.2em;
        right:15em;
        text-align: right;
        font-size: 12px;
    }

    .date_text {
        position: absolute;
        top: 38em;
        left:19em;
        text-align: left;
        font-size: 12px;
    }

    .signature_text {
        position: absolute;
        top: 38em;
        right:16em;
        text-align: right;
        font-size: 12px;
    }

    .serial {
        position: absolute;
        bottom: 4em;
        right:4em;
        width: 100%;
        text-align: right;
        font-size: 12px;
    }
.logos {
    position: absolute;
    bottom: 12mm;
    left: 0;
    width: 100%;
    text-align: center;
}

.logos img {
    height: 18mm;
    margin: 0 5mm;
}

.signatureImage {
    max-width: 200px;
    height: auto;
    display: block;
    margin: 0 auto 5em auto; /* bottom spacing */
}
</style>

<div class="page">

    <!-- Background image — only reliable method in DomPDF -->
    <img class="background" src="{{ public_path('images/certificateAssets/rba-single-module-certificate.webp') }}" />

    <!-- Your certificate content goes on top -->
    <div class="content">
        <p class='title'>Module Completion Badge</p>
    </div>

    <div class="congratulations">
        <p>Congratulations!</p>
    </div>

    <div class="name">
        <p>{!! $payload['name'] !!}</p>
    </div>

    <div class="body">
        <p class="jobrole" style="margin-bottom: 0.5em;">{!! $payload['jobrole'] !!}, {!! $payload['company'] !!}</p>
        <p style="margin: 0 5em">For Successfully Completing  {!! $payload['course'] !!} of the Rwanda Bankers Association</p>
    </div>

    <div class="date">
        <p style="margin-bottom: 0.5em; font-family:bold;">{!! $payload['date'] !!}</p>
    </div>

    <div class="signature">
        <p>
            <img class="signatureImage" src="{{ public_path('images/certificateAssets/CEO-Signature-v2.png') }}" />
        </p>
    </div>

    <div class="serial">
        <p style="margin-bottom: -5px;">{!! $payload['serial'] !!}</p>
    </div>

    <div class="date_text">
        <p>Date</p>
    </div>

<div class="signature_text">
    <p style="text-align: center; margin: 0;">
        Tony Francis NTORE <br> Chief Executive Officer
    </p>
</div>
<div class="logos">

    @foreach($payload['partners'] as $partner)
        @php
        $path = public_path(parse_url($partner->image('partner_logo'), PHP_URL_PATH));
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        @endphp
        <img src="{{ $base64 }}">
    @endforeach
</div>
</div>
