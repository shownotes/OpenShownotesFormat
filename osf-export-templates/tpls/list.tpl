{% macro shownote(note) %}

{% if 'chapter' in note.tags %}
  <h2><span>{{note.timestamp | htime}}</span> {{note.title}}</h2>
{% else %}
  {% if note.url %}
    <a href="{{note.url}}" data-tooltip="{{note.timestamp | htime}}">{{note.title}}</a>
  {% else %}
    <span data-tooltip="{{note.timestamp | htime}}">{{note.title}}</span>
  {% endif %}
{% endif %}

<ul>
{% for subnote in note.shownotes %}
  <li>
    {{ shownote(subnote) }}
  </li>
{% endfor %}
</ul>

{% endmacro %}

<div class="document-list">

{% for note in shownotes %}
  {{ shownote(note) }}
{% endfor %}

</div>
