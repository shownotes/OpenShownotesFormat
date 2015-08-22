{% macro shownote(note) -%}
  {%- if note.timestamp != null -%}
M0,"{{note.title | replace("\"", "\"\"") | safe}};{{note.url}};
{%- for tag in note.tags %} #{{tag}} {%- endfor %}",{{note.timestamp | htime(true)}},,,
{% endif %}
{%- for subnote in note.shownotes -%}
  {{ shownote(subnote) }}
{%- endfor -%}
{% endmacro %}

{%- for note in shownotes -%}
  {{ shownote(note) }}
{%- endfor -%}


