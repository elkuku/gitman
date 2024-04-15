import {Controller} from '@hotwired/stimulus';
import {Modal} from 'bootstrap';
import '../lib/prism.js'
import '../styles/prism.css'

export default class extends Controller {
    static targets = [
        'statusCommits', 'statusFiles',
        'filelist', 'filediff', 'debug',
        'numCommitsBehind', 'numCommitsAhead',
        'numFilesAdded', 'numFilesModified', 'numFilesDeleted', 'numFilesUntracked',
        'branchInfo'
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
        console.log(info);
        this.info = info

        this.numCommitsBehindTarget.innerHTML = info.commitsBehind ? info.commitsBehind : '';
        this.numCommitsAheadTarget.innerHTML = info.commitsAhead ? info.commitsAhead : '';

        this.numFilesAddedTarget.innerHTML = info.added ? info.added : '';
        this.numFilesModifiedTarget.innerHTML = info.modified ? info.modified : '';
        this.numFilesDeletedTarget.innerHTML = info.deleted ? info.deleted : '';
        this.numFilesUntrackedTarget.innerHTML = info.untracked ? info.untracked : '';

        this.statusFilesTarget.className = ''
        this.statusFilesTarget.classList.add('badge')
        this.statusFilesTarget.innerHTML = '&nbsp;'
        if (info.isValid) {
            this.branchInfoTarget.innerHTML = info.branchInfo
            if (info.hasFileChanges) {
                this.statusFilesTarget.classList.add('bg-warning')
                for (const file of info.changedFiles) {
                    const li = document.createElement("li");
                    li.className = 'list-group-item';
                    switch (file.status) {
                        case 'M':
                            li.classList.add('text-warning', 'link-like');
                            li.setAttribute('data-action', 'click->repository#showDiff')
                            break;
                        case 'A':
                            li.classList.add('text-success');
                            break;
                        case 'D':
                            li.classList.add('text-danger');
                            break;
                        case '?':
                            li.classList.add('text-info');
                            break;
                    }
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
