@twillBlockTitle('FAQ list')
@twillBlockIcon('text')
@twillBlockGroup('app')

<x-twill::input name="eyebrow" label="Small heading" :translated="true" />
<x-twill::input name="title" label="Section heading" :translated="true" />
@formField('block_editor', ['name' => 'faq_items', 'blocks' => ['faq_item']])
