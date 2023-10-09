{{/*
Selector labels
*/}}
{{- define "php.selectorLabels" -}}
app.kubernetes.io/name: {{ include "pel-chart.fullname" . }}-php
app.kubernetes.io/instance: {{ .Release.Name }}-php
{{- end }}
