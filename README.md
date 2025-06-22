# Logger API – Kubernetes zadanie

Jednoduché PHP API, ktoré loguje každý HTTP request do súboru `/logs/requests.log`.


Prikazy + postup ako sme to spravili:
1. Po vytvoreni suborov sme pouzili tieto prikazy:
2. docker build -t php-logger:latest .  - buildly sme Docker image
3. kubectl apply -f k8s/   aplikovali sme vsetky kubernetes komponenty
4.  a) http://localhost:30082  - otestovali sme aplikaciu
5.  b) curl http://localhost:30082/api/test - druhy spôsob cez terminal
6.  kubectl exec -it -n exam-samuel deploy/logger-app -- cat /logs/requests.log - overili sme log. subor
7.  Obsahoval nieco taketo:
8.  a) 2025-06-22 21:13:50 GET /api/test

Opis suborov:
1. namespace.yaml vytvorí izolované prostredie exam-samuel

2. configmap.yaml nastaví environment premennú LOG_DIR=/logs pre aplikáciu

3. pv.yaml + pvc.yaml zabezpečí trvalý (perzistentný) disk pre ukladanie logov

4. deployment.yaml vytvorí kontajner (pod) s aplikáciou, ktorý mountne volume a použije ConfigMap

5. service.yaml sprístupní aplikáciu cez port 30082 (NodePort)

6. index.php	Aplikácia – loguje každý HTTP request do requests.log
7. Dockerfile   Vytvára image s PHP a našou aplikáciou

BONUS:
Upravili sme subory configmap.yaml, deployment.yaml, index.php, a pridali secret.yaml
Prikazy pouzite:
1. docker build -t php-logger:latest .
2. kubectl apply -f k8s/secret.yaml
3. kubectl apply -f k8s/configmap.yaml
4. kubectl apply -f k8s/deployment.yaml
5. kubectl delete pod -n exam-samuel -l app=logger  - RESTARTOVANIE PODu
6. Testovanie:
7. kubectl exec -it -n exam-samuel deploy/logger-app -- ls /logs  - ci obsahuje log subor
8. curl http://localhost:30082/
9. kubectl get pods -n exam-samuel -w  -sledujeme stavy 
10. Ak by si napr. zablokoval index.php alebo spôsobil error, Kubernetes kontajner reštartne (liveness) alebo ho neoznačí ako "Ready" (readiness).

1. secret.yaml: Ukladá názov log súboru ako tajnú premennú.
2. ConfigMap - Umožňuje meniť správanie aplikácie (napr. výstup) bez potreby rebuildu.
3. Liveness probe - 	Kubernetes kontroluje, či kontajner žije. Ak nie, reštartne ho.
4. Readiness probe - Kubernetes čaká, kým je aplikácia pripravená, než ju sprístupní cez Service.


## Nasadenie

```bash
kubectl apply -f k8s/namespace.yaml
kubectl apply -f k8s/configmap.yaml
kubectl apply -f k8s/pv.yaml
kubectl apply -f k8s/pvc.yaml
kubectl apply -f k8s/deployment.yaml
kubectl apply -f k8s/service.yaml
