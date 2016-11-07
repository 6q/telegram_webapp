function Pager(tableName, itemsPerPage) {
    this.tableName = tableName;
    this.itemsPerPage = itemsPerPage;
    this.currentPage = 1;
    this.pages = 0;
    this.inited = false;
    
    this.showRecords = function(from, to) {        
        var rows = document.getElementById(tableName).rows;
        // i starts from 1 to skip table header row
        for (var i = 1; i < rows.length; i++) {
            if (i < from || i > to)  
                rows[i].style.display = 'none';
            else
                rows[i].style.display = '';
        }
    }
    
    this.showPage = function(pagerName,pageNumber) {
    	if (! this.inited) {
    		alert("not inited");
    		return;
    	}

        var oldPageAnchor = document.getElementById(pagerName+this.currentPage);
        oldPageAnchor.className = 'pg-normal';
        
        this.currentPage = pageNumber;
        var newPageAnchor = document.getElementById(pagerName+this.currentPage);
        newPageAnchor.className = 'active';
        
        var from = (pageNumber - 1) * itemsPerPage + 1;
        var to = from + itemsPerPage - 1;
        this.showRecords(from, to);
    }   
    
    this.prev = function(pagerName) {
        if (this.currentPage > 1)
            this.showPage(pagerName,this.currentPage - 1);
    }
    
    this.next = function(pagerName) {
        if (this.currentPage < this.pages) {
            this.showPage(pagerName,this.currentPage + 1);
        }
    }                        
    
    this.init = function() {
        var rows = document.getElementById(tableName).rows;
        var records = (rows.length - 1); 
        this.pages = Math.ceil(records / itemsPerPage);
        this.inited = true;
    }

    this.showPageNav = function(pagerName, positionId) {
    	if (! this.inited) {
    		alert("not inited");
    		return;
    	}
    	var element = document.getElementById(positionId);
    	
    	var pagerHtml = '<li> <a href="javascript:void(0);" onclick="' + pagerName + '.prev('+"'"+pagerName+"'"+');"> &#171</a></li>';
        for (var page = 1; page <= this.pages; page++) 
    pagerHtml += '<li id="'+pagerName+ page + '"> <a href="javascript:void(0);" class="pg-normal" onclick="'+pagerName+ '.showPage('+"'"+pagerName+"'"+',' + page + ');">' + page + '</a></li>';
        pagerHtml += '<li> <a href="javascript:void(0);" onclick="'+pagerName+'.next('+"'"+pagerName+"'"+');" class="pg-normal">&#187;</a></li>';
        
        element.innerHTML = pagerHtml;
    }
}

