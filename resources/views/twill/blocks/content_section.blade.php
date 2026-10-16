@twillBlockTitle('Content section')
@twillBlockIcon('text')
@twillBlockGroup('app')

<x-twill::input name="eyebrow" label="Small heading" :translated="true" />
<x-twill::input name="title" label="Section heading" :translated="true" />
<x-twill::wysiwyg name="text" label="Section text" :translated="true" />
<x-twill::medias name="image" label="Section image" :max="1" />
