from distutils.core import setup

setup(name='leveldb-shell',
      version='dev',
      description='LevelDB Shell',
      classifiers=[
          'Topic :: Database :: Front-Ends',
          'Intended Audience :: Developers',
          'License :: OSI Approved :: MIT License',
          'Operating System :: POSIX'
      ],
      author='Shaun Li',
      author_email='shonhen@gmail.com',
      url='https://github.com/shaunlee/LevelDB-Shell',
      license='MIT',
      install_requires=['leveldb'],
      packages=['leveldb-shell'],
      scripts=['leveldb-shell/leveldb-shell'],
     )
