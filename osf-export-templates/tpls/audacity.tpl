{% macro shownote(note) -%}
  {%- if note.timestamp != null -%}
    {{note.timestamp / 1000 | round(6)}}	{{note.timestamp / 1000 | round(6)}}	{{note.title|safe}}
    {%- if note.url %} <{{note.url}}>{% endif -%}
    {%- for tag in note.tags %} #{{tag}} {%- endfor %}
{% for subnote in note.shownotes -%}
  {{ shownote(subnote) }}
{%- endfor -%}
  {%- endif -%}
{% endmacro %}

{%- for note in shownotes -%}
  {{ shownote(note) }}
{%- endfor -%}

