import {Controller} from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['statusCommits', 'statusFiles', 'debug']
    static values = {
        path: String
    }

    connect() {
        console.log('ON')
        this.update()
    }

    async update() {
        this.statusFilesTarget.className = ''
        this.statusFilesTarget.classList.add('badge', 'bg-info')
        this.statusFilesTarget.innerHTML = 'Loading...'

        const response = await fetch("/repoinfo?path="+this.pathValue);
        const info = await response.json();
        console.log(info);

        this.statusFilesTarget.className = ''
        this.statusFilesTarget.classList.add('badge')
        this.statusFilesTarget.innerHTML = '&nbsp;'
        if (info.isValid){
            if (info.hasFileChanges){
                this.statusFilesTarget.classList.add('bg-warning')
            }else{
                this.statusFilesTarget.classList.add('bg-success')
            }
            if (info.hasCommitChanges){
                this.statusCommitsTarget.classList.add('bg-warning')
            }else{
                this.statusCommitsTarget.classList.add('bg-success')
            }
            this.debugTarget.innerHTML = info.debugOutput
        }else{
            this.statusFilesTarget.classList.add('bg-secondary')
            this.statusCommitsTarget.classList.add('bg-secondary')
        }
    }
}
