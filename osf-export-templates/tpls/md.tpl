{% macro shownote(note) -%}

{%- if 'chapter' in note.tags %}
## {{note.title | safe}} {% if note.timestamp != null %}```{{note.timestamp | htime}}```{% endif %}
{%- else -%}
  {{ "* "|indent((note.level + 1) * 2, true)|replace("\n", "")|replace("    ", "", 1) }}
  {%- if note.url -%}
    [{{note.title | safe}}]({{note.url}})
  {%- else -%}
    {{note.title | safe}}
  {%- endif -%}
{%- endif %}
{% for subnote in note.shownotes -%}
  {{ shownote(subnote) }}
{%- endfor -%}

{%- endmacro %}


{%- for note in shownotes -%}
  {{ shownote(note) }}
{%- endfor %}


