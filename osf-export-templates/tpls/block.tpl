{% macro shownote(note) %}
	
{% if 'chapter' in note.tags %}
  <dl>
    <dt>{{note.timestamp | htime}}</dt>
    <dd>
      <h2>{{note.title}}</h2>
{% else %}
  {% if note.timestamp != null -%}
  <span data-tooltip="{{note.timestamp | htime}}">
  {%- else -%}
  <span>
  {%- endif -%}
  {%- if note.url -%}
    <a href="{{note.url}}">{{note.title}}</a>
  {%- else -%}
    {{note.title}}
  {%- endif -%}
  </span>
{% endif %}

{% for subnote in note.shownotes %}
    {{ shownote(subnote) }}
{% endfor %}
  
{% if 'chapter' in note.tags %}</dd></dl>{% endif %}

{% endmacro %}

<div class="document-block">

{% for note in shownotes %}
  {{ shownote(note) }}
{% endfor %}

</div>

