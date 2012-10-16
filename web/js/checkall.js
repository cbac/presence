$(document).ready(function()
		{
			$("#presence_all").click(function()				
			{
				var checked_status = this.checked;
				$("input").each(function()
				{
					if(this.type=='checkbox'){
						this.checked = checked_status;
					}
				});
			});					
		});