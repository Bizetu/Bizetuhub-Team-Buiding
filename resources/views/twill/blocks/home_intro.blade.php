@twillBlockTitle('Homepage introduction')
@twillBlockIcon('text')
@twillBlockGroup('homepage')

<x-twill::input name="eyebrow" label="Eyebrow" :translated="true" />
<x-twill::input name="title" label="Heading" :translated="true" />
<x-twill::wysiwyg name="text" label="Introduction" :translated="true" />
