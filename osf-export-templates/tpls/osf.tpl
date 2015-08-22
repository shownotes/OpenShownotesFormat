{% macro headerValue(line, val) %}
  {%- if line.valuetype == "person" -%}
    {{ val.name }} <{{ val.uri }}>
  {%- else -%}
    {{ val }}
  {%- endif -%}
{% endmacro %}

{%- set comma = joiner(", ") -%}
HEADER
{% for line in header.pairs -%}
  {{ line.key }}: {% if line.type == "multiple" -%}
    {% for val in line.value -%}
      {{ comma() }}{{ headerValue(line, val) }}
    {%- endfor %}
  {%- else -%}
    {{ line.value }}
  {%- endif %}
{% endfor -%}
/HEADER

{% macro shownote(note) -%}
  {%- if 'chapter' in note.tags %}{# line break #}
{% endif -%}
  {% if note.timestamp != null %}{{note.timestamp | htime}} {% endif -%} {{note.title | safe}}
  {%- if note.url %} <{{note.url}}> {%- endif -%}
  {%- for tag in note.tags %} #{{ tag }} {%- endfor %}
{% for subnote in note.shownotes -%}
    {{ shownote(subnote) }}
  {%- endfor %}
{%- endmacro %}

{%- for note in shownotes -%}
  {{ shownote(note) }}
{%- endfor -%}
