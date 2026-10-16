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
        top: 10.5em;
        position: relative;
        text-align: center;
        width: 100%;
        height: 100%;
        text-transform: uppercase;
    }

    .title {
        position: absolute;
        width: 100%;
        text-align: center;
        font-size: 32px;
        font-family: 'Aptos-Bold';
        color: #1F3B67;
    }

    .congratulations {
        position: absolute;
        top: 13.5em;
        width: 100%;
        text-align: center;
        font-family: 'Aptos-Bold';
        font-size: 24px;
        text-transform: uppercase;

    }

    .name {
        position: absolute;
        top: 16em;
        width: 100%;
        text-align: center;
        font-size: 32px;
        color: #1F3B67;
        font-family: 'Aptos-Bold';
    }

    .body {
        position: absolute;
        top: 19.5em;
        width: 100%;
        text-align: center;
        font-size: 24px;
        font-family: 'Aptos-Bold';
        color:#1F3B67;
    }

    .jobrole{
        font-family: 'Aptos';
        font-size: 22px;
        color: black;
    }
    .modules {
        position: absolute;
        top: 24.5em;
        width: 100%;
        text-align: center;
        font-size: 24px;
        font-family: 'Aptos-Regular';
    }

    .date {
        position: absolute;
        bottom: 5em;
        width: 100%;
        text-align: center;
        font-size: 14px;
    }

    .signature {
        position: absolute;
        bottom: 5em;
        right: 6em;
        text-align: right;
        font-size: 12px;
    }

    .date_text {
        position: absolute;
        bottom: 8.5em;
        left: 50%;
        text-align: center;
        font-size: 18px;
    }

    .signature_text {
        position: absolute;
        bottom: 5.5em;
        right:8em;
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
    text-align: left;
    margin-left:4em;
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
    <img class="background" src="{{ public_path('images/certificateAssets/rba-certificate-background.jpg') }}" />

    <!-- Your certificate content goes on top -->
    <div class="content">
        <p class='title'>Certificate of Completion</p>
    </div>

    <div class="congratulations">
        <p>This certificate is presented to</p>
    </div>

    <div class="name">
        <p>{!! $payload['name'] !!}</p>
    </div>

    <div class="body">
        <p class="jobrole">{!! $payload['jobrole'] !!}, {!! $payload['company'] !!}</p>
        <p>For Successfully Completing the ESG Programme.</p>
    </div>

<div class="modules">
  <table style="margin: 0 auto; border-collapse: collapse; width: auto;">
    <tbody>
      <tr>
        <?php foreach ($payload['columns'] as $index => $column): ?>
        <td valign="top" style="padding: 0 1.5em; vertical-align: top; text-align:left;">
          <?php foreach ($column as $item): ?>
          <div style="display:block; font-family:'Aptos-Regular'; font-size:16px; line-height:1.5em;">
            <span style="font-weight:bold; padding-right:0.5em;">-</span>
            <?= htmlspecialchars($item) ?>
          </div>
          <?php endforeach; ?>
        </td>
        <?php endforeach; ?>
      </tr>
    </tbody>
  </table>
</div>
    <div class="signature">
        <p>
            <img class="signatureImage" src="{{ public_path('images/certificateAssets/CEO-Signature-v2.png') }}" />
        </p>
    </div>

    {{-- <div class="serial">
        <p style="margin-bottom: -5px;">{!! $payload['serial'] !!}</p>
    </div> --}}

    <div class="date_text">
        <p>Date: {!! $payload['date'] !!}</p>
    </div>

<div class="signature_text">
    <p style="text-align: center; margin: 0;">
        Tony Francis NTORE <br> Chief Executive Officer
    </p>
</div>
<div class="logos">
<div class="signature_text">
    <p style="text-align: center; margin: 0;">
        Tony Francis NTORE <br> Chief Executive Officer
    </p>
</div>
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
