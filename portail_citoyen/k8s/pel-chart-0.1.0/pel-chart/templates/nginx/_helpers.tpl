{{/*
Selector labels
*/}}
{{- define "nginx.selectorLabels" -}}
app.kubernetes.io/name: {{ include "pel-chart.fullname" . }}-nginx
app.kubernetes.io/instance: {{ .Release.Name }}-nginx
{{- end }}
