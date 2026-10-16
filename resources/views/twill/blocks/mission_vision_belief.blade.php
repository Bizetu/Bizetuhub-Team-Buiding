@twillBlockTitle('Mission, vision & belief')
@twillBlockIcon('text')
@twillBlockGroup('app')

<x-twill::input name="vision_title" label="Vision heading" :translated="true" />
<x-twill::wysiwyg name="vision_text" label="Vision" :translated="true" />

<x-twill::input name="mission_title" label="Mission heading" :translated="true" />
<x-twill::wysiwyg name="mission_text" label="Mission" :translated="true" />

<x-twill::input name="belief_title" label="Belief heading" :translated="true" />
<x-twill::wysiwyg name="belief_text" label="Belief" :translated="true" />
