#copied from remd

import mimetypes
mimetypes.init()
#global mimetypes = {"txt" : "text/plain", "jpg" : "image/jpeg", "jpeg" : "image/jpeg",\
#"png" : "image/png", "css" : "text/css", "gif" : "image/gif"}

class FileServer(object):
    def __init__(self, filename):
        self.content_type = mimetypes.guess_type(filename)[0]
        self.filename = filename

    def __call__(self, environ, start_response):
        try:
            fp = open(self.filename)
            print "OPEN\n\n\n"
        except OSerror:
            start_response("404 not found", [('Content-type', 'text/html'),])
            print "NOT OPEN\n\n\n"
            return 'file not found'

        data = fp.read()
        start_response("200 OK", [('Content-type', self.content_type),])
        print "The DATA FROM FILE SERVER",data
        return data
