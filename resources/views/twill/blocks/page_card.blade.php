@twillBlockTitle('Card')
@twillBlockIcon('text')
@twillBlockGroup('app')

<x-twill::input name="title" label="Card title" :translated="true" />
<x-twill::wysiwyg name="text" label="Card text" :translated="true" />
<x-twill::medias name="background" label="Background image" :max="1" />
<x-twill::input name="link_label" label="Link label" :translated="true" />
<x-twill::input name="link_url" label="Link URL" />
