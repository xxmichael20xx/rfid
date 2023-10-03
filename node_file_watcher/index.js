const fs = require('fs');
const readline = require('readline');
const Pusher = require('pusher');
const chokidar = require('chokidar');

const pusher = new Pusher({
  appId: '1675760',
  key: '915d8624994e86abe8ec',
  secret: '20247ac91e161bf4cfe7',
  cluster: 'ap1',
  useTLS: true,
});

const filePath = 'C:/Users/Michael/OneDrive/Desktop/Beeps.txt'; // Replace with the path to your file

// Function to push the last line of the file to Pusher
const pushLastLine = () => {
  const rl = readline.createInterface({
    input: fs.createReadStream(filePath),
    crlfDelay: Infinity,
  })

  let lastLine = ''
  rl.on('line', (line) => lastLine = line)

  rl.on('close', () => {
    pusher.trigger('my-channel', 'scan-id', { 'id': lastLine })
    console.log('Scanned ID: ' + lastLine)
  })
}

// Watch the file for changes using chokidar
const watcher = chokidar.watch(filePath)

watcher.on('change', () => pushLastLine())
console.log('Watching file for scanned ids...')
