{set-block scope=root variable=subject}Object state changed{/set-block}
{set-block scope=root variable=content_type}text/html{/set-block}
<p>Hi,</p>

<p>The object "{$content_object.name|wash}" has been setted to "{$state.current_translation.name}" :<br>
	<a href="{$content_object.main_node.url_alias|ezurl(no, full)}">{$content_object.main_node.url_alias|ezurl(no, full)}</a>
</p>
<p>Thanks.</p>
	