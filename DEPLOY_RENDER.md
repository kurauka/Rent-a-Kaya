# Deploy to Render.com

This repo includes a `Dockerfile` and `render.yaml` for deploying to Render as a Docker Web Service.

Files added: [Dockerfile](Dockerfile), [docker-start.sh](docker-start.sh), [.dockerignore](.dockerignore), [render.yaml](render.yaml)

The `docker-start.sh` script updates Apache to listen on the port provided by Render via the `PORT` environment variable before starting Apache.

Quick steps:

```bash
git add Dockerfile docker-start.sh .dockerignore render.yaml
git commit -m "Add Render Docker deployment files"
git push origin main
```

On Render:
- Create a new Web Service, choose Docker, connect your repository and branch (e.g., `main`).
- Ensure `dockerfilePath` is `Dockerfile` (or set it in the UI).
- Add environment variables required by the app (DB host/user/password and any API keys) in the Render dashboard.

Render will build and deploy the service automatically. If you prefer infrastructure as code, the provided `render.yaml` is a template you can edit before committing.
