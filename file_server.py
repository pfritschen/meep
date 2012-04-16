import mimetypes
mimetypes.init()

class FileServer(object):
    def __init__(self, filename):
        self.content_type = mimetypes.guess_type(filename)[0]
        self.filename = filename

    def __call__(self, environ, start_response):
        try:
            fp = open(self.filename)
            print "OPENING:" + self.filename
          
        except OSerror:
            start_response("404 not found", [('Content-type', 'text/html'),])
            print "NOT OPENING:" + self.filename
            return 'file not found'

        data = fp.read()
        print data
        print self.content_type
        start_response("200 OK", [('Content-type', self.content_type),])
        
        return data
