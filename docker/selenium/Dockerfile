FROM selenium/standalone-chrome-debug

RUN sudo usermod -a -G video seluser
RUN x11vnc -storepasswd "password" /home/seluser/.vnc/passwd
