FM.Search = {
	showPage : function(pageNo, totalPages) {
		i = 0;
		while(i < totalPages) {
			if($('result_' + i)) {
				$('result_' + i).style.display = 'none';
			}
			i += 1;
		}
		$('result_' + i).style.display = 'inline;';
	},

	toggleSubcats : function(id) {
		if($('sub_' + id)) {
			Effect.toggle('sub_' + id, 'blind')
		}
	}
}