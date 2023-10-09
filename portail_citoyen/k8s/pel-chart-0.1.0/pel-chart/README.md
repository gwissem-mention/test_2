# pel-chart

```bash
kubectl create ns pel-sample
kubectl label namespaces pel-sample istio-injection=enabled --overwrite=true
helm upgrade -i pel-sample . -n pel-sample -f values.yaml
```
