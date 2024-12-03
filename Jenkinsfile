pipeline {
    agent {
        kubernetes {
            yaml """
apiVersion: v1
kind: Pod
metadata:
  name: buildah-pod
spec:
  containers:
  - name: buildah
    image: docker.io/jairodh/buildah:v2
    command:
    - cat
    tty: true
    securityContext:
      privileged: true
    volumeMounts:
      - name: varlibcontainers
        mountPath: /var/lib/containers
  volumes:
    - name: varlibcontainers
      emptyDir: {}
"""
        }
    }
    options {
        buildDiscarder(logRotator(numToKeepStr: '10'))
        durabilityHint('PERFORMANCE_OPTIMIZED')
        disableConcurrentBuilds()
    }
    stages {
        stage('Clonar repositorio') {
            steps {
                git branch: 'main', url: "${REPO_URL}"
            }
        }
        stage('Crear y subir imagen') {
            steps {
                container('buildah') {
                    script {
                        // Construcción de la imagen
                        sh "buildah build -t ${IMAGE}:${BUILD_NUMBER} ."

                        // Login en DockerHub utilizando las credenciales de Jenkins
                        withCredentials([usernamePassword(credentialsId: 'docker_hub', usernameVariable: 'DOCKER_USER', passwordVariable: 'DOCKER_PASS')]) {

                        // Realizar login en DockerHub
                            sh "echo ${DOCKER_PASS} | buildah login -u ${DOCKER_USER} --password-stdin docker.io"
                        }
                         
                        // Push de la imagen
                        sh "buildah push ${IMAGE}:${BUILD_NUMBER} docker://docker.io/${IMAGE}:${BUILD_NUMBER}"
                    }
                }
            }
        }
        stage('Deployment') {
            agent any
            steps {
                script {
                    sshagent(credentials: ['VPS_SSH']) {
                        // Actualizar el repositorio en el VPS
                        sh "ssh -o StrictHostKeyChecking=no jairo@fekir.touristmap.es 'cd /home/jairo/Keptn-k3s && git pull'"

                        // Desplegar en el VPS
                        sh "ssh -o StrictHostKeyChecking=no jairo@fekir.touristmap.es 'kubectl --kubeconfig=${KUBE_CONFIG} apply -f /home/jairo/Keptn-k3s/k3s/wordPress-deployment.yaml'"
                        sh "ssh -o StrictHostKeyChecking=no jairo@fekir.touristmap.es 'kubectl --kubeconfig=${KUBE_CONFIG} apply -f /home/jairo/Keptn-k3s/k3s/wordPress-srv.yaml'"
                        sh "ssh -o StrictHostKeyChecking=no jairo@fekir.touristmap.es 'kubectl --kubeconfig=${KUBE_CONFIG} apply -f /home/jairo/Keptn-k3s/k3s/ingress.yaml'"
                    }
                }
            }
        }
    }
}
