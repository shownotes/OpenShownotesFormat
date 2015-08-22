{%- for note in shownotes -%}
{% if 'chapter' in note.tags -%}
  {{note.timestamp | htime}} {{note.title}}
{% endif -%}
{% endfor %}

