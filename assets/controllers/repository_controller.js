import {Controller} from '@hotwired/stimulus';
import {Modal} from 'bootstrap';
import '../lib/prism.js'
import '../styles/prism.css'

export default class extends Controller {
    static targets = ['statusCommits', 'statusFiles', 'filelist', 'filediff', 'debug',
    'numFilesAdded', 'numFilesModified', 'numFilesDeleted'
    ]
    static values = {
        path: String
    }

    modal
    info

    connect() {
        this.update()
        this.modal = new Modal('#exampleModal')
    }

    async update() {
        this.statusFilesTarget.className = ''
        this.statusFilesTarget.classList.add('badge', 'bg-info')
        this.statusFilesTarget.innerHTML = 'Loading...'

        const response = await fetch(`/repoinfo?path=${this.pathValue}`);
        const info = await response.json();

        this.info = info

        this.numFilesModifiedTarget.innerHTML = info.modified ? info.modified  : '';
        this.statusFilesTarget.className = ''
        this.statusFilesTarget.classList.add('badge')
        this.statusFilesTarget.innerHTML = '&nbsp;'
        if (info.isValid) {
            if (info.hasFileChanges) {
                this.statusFilesTarget.classList.add('bg-warning')
                for (const file of info.changedFiles) {
                    const li = document.createElement("li");
                    li.className = 'list-group-item';
                    if ('M' === file.status) {
                        li.classList.add('text-warning', 'link-like');
                        li.setAttribute('data-action', 'click->repository#showDiff')
                    }
                    li.appendChild(document.createTextNode(file.path));
                    this.filelistTarget.appendChild(li);
                }
            } else {
                this.statusFilesTarget.classList.add('bg-success')
            }
            if (info.hasCommitChanges) {
                this.statusCommitsTarget.classList.add('bg-warning')
            } else {
                this.statusCommitsTarget.classList.add('bg-success')
            }
            this.debugTarget.innerHTML = info.debugOutput
        } else {
            this.statusFilesTarget.classList.add('bg-secondary')
            this.statusCommitsTarget.classList.add('bg-secondary')
        }
    }

    async showDiff(e) {
        const response = await fetch(`/filediff?repo=${this.pathValue}&file=${e.target.innerText}`);
        const info = await response.json();

        const el = document.getElementById('filediff')

        el.innerHTML = Prism.highlight(info.text, Prism.languages.diff, 'diff');

        this.modal.show();
    }
}
