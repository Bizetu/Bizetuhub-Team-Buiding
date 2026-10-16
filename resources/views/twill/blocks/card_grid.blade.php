@twillBlockTitle('Card grid')
@twillBlockIcon('text')
@twillBlockGroup('app')

<x-twill::input name="eyebrow" label="Small heading" :translated="true" />
<x-twill::input name="title" label="Section heading" :translated="true" />
<x-twill::wysiwyg name="text" label="Introduction" :translated="true" />
@formField('block_editor', ['name' => 'card_items', 'blocks' => ['page_card']])
