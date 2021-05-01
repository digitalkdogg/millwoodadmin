"use strict";

$(document).ready(function () {
	var admin = {
		evars : {},
		data : {},
		fn : {
			init : function (admin) {
				admin.evars = env;
				env = null;
				try {
					admin.evars['secure'] = secure;
					secure = null;
				} catch(e) {console.log(e);}
				try {
					admin.evars['sessionid'] = session_id;
					session_id = null;
				} catch(e) {console.log(e);}
				try {
					if (admin.utils.getUrlParam()['access_token'] != undefined) {
						admin.evars['secret']  = admin.utils.getUrlParam()['access_token']
					} 
				} catch(e) {admin.evars['secret']=false; console.log(e);}
				try {
					admin.evars['auth_url'] = auth_url;
					auth_url = null;
				} catch (e) {console.log(e);}	
	
			}, 
			get_db_campaigns: function () {
				$.ajax({
					'url': 'rest/get_db_campaigns.php',
					'type': 'POST',
					'data' : {'sessionid' : admin.evars.sessionid},
					'success' : function (data) {
						admin.fn.output_campaigns_db(data);
					}
				})
			},
			get_cc_guts: function (id) {
			//	var id = $($this).attr('data-id');
				$.ajax({
					'url': 'rest/get_cc_guts.php',
					'type': 'POST',
					data : {'cc_id': id, 'access_token': admin.evars.secret},
					'success': function (data) {
						data = JSON.parse(data);

						$.each(data, function () {
							if (typeof(this)=='object') {
								$.each(this, function () {
									if (this.role == 'permalink') {
										var dataarr = Array();
										dataarr['campaign_activity_id']= this.campaign_activity_id;
										admin.utils.update_campaign_rec(admin.data.cc_campaigns.campaigns, id, dataarr);
										admin.fn.get_cc_permalink(id);
									}
								})
							}
						})
					}

				})
			},
			get_cc_permalink: function (id) {
				var rec = admin.utils.lookup_campaign_rec(admin.data.cc_campaigns.campaigns, id);
				$.ajax({
					'url': 'rest/get_cc_permalink.php',
					'type': 'POST',
					'data': {'access_token': admin.evars.secret, 'acc_id': rec.campaign_activity_id},
					'success': function (data) {
						data = JSON.parse(data);
						
						var link = $('div.panel-body.cc-campaigns .row#cc_'+id+' i.link');
						$(link).attr('data-href', data['permalink_url']);

						admin.utils.update_campaign_rec(admin.data.cc_campaigns.campaigns, id, data);
					}
				})

			},
			reg_login_btn: function () {			
				$('#login #submit').click(function () {
					console.log('hi');
					$('status-row div#status').empty()
					$.ajax({
						'url':'rest/session_me.php',
						'type' : 'POST',
						'data': {'sessionid': admin.evars.sessionid, 'pin': $('#pin').val().trim() },
						'success': function (data) {
							data = JSON.parse(data);
							if (data.status == "success") {
								$('#status-row div#status').html(data.msg);
								window.location.href = admin.evars.auth_url;
							} else if (data.status == "error") {
								$('#status-row div#status').html('<span class = "error">'+data.msg+'</span>');
							}
						}
					})
				})
			},
			'output_campaigns_db' : function (data) {

				data = JSON.parse(data);
				admin.data['db_campaigns'] = data;

				data.sort((a, b) => (a.last_updated > b.last_updated) ? 1 : -1)

				if (data.length > 0) {
					$('#success-wrap #db-campaigns>.panel-body').empty()
					$.each(data, function () {
						var $this = this;
						$('<div />', {
							'class': 'row',
							'id': 'db_' + this.cc_id
						}).prependTo('#success-wrap #db-campaigns>.panel-body')
						$('<div />', {
							'class': 'col-lg-4',
							'text' : this.title
						}).appendTo('#success-wrap #db-campaigns .row#db_'+this.cc_id)
						$('<div />', {
							'class': 'col-lg-1',
							'text' : this.status
						}).appendTo('#success-wrap #db-campaigns .row#db_'+this.cc_id)
						$('<div />', {
							'class': 'col-lg-2',
							'text' : new Date(this.created_at).toLocaleDateString()
						}).appendTo('#success-wrap #db-campaigns .row#db_'+this.cc_id)
						$('<div />', {
							'class': 'col-lg-2',
							'text' : new Date(this.last_updated).toLocaleDateString()
						}).appendTo('#success-wrap #db-campaigns .row#db_'+this.cc_id)
						$('<i />', {
							'class' : 'fas fa-link db_link notadded',
							'data-id': this.cc_id,
							'data-href': this.permalink_url,
							'click': function () {window.open($this.permalink_url)}
						}).appendTo('#success-wrap #db-campaigns .row#db_'+this.cc_id)

					})
				}
			},
			'output_campaigns_cc': function (data) {
				data = JSON.parse(data)
				admin.data['cc_campaigns']=data;
				$('#success-wrap #cc-campaigns>.panel-body #result-wrap').empty()


				if (data.campaigns != undefined ) {

					$.each(data.campaigns, function (index, val) {
						if (admin.utils.lookup_campaign_rec(admin.data.db_campaigns, this.campaign_id) == undefined) {
						
						   if (this.current_status=='Done') {
    							var $this = this;
    							$('<div />', {
    								'class': 'row',
    								'id': 'cc_'+this.campaign_id
    							}).appendTo('#success-wrap #cc-campaigns>.panel-body #result-wrap')
    							
    							$('<div />', {
    								'class': 'col-lg-5',
    								'text' : this.name
    							}).appendTo('#success-wrap #cc-campaigns .row#cc_'+this.campaign_id)
    
    							$('<div />', {
    								'class': 'col-lg-2',
    							'text' : this.current_status
    							}).appendTo('#success-wrap #cc-campaigns .row#cc_'+this.campaign_id)
    
    							$('<div />', {
    								'class': 'col-lg-2',
    								'text' : new Date(this.updated_at).toLocaleDateString()
    							}).appendTo('#success-wrap #cc-campaigns .row#cc_'+this.campaign_id)
    
    							if (this.current_status=='Done') {
    								$('<i />', {
    									'class' : 'fas fa-link link notadded',
    									'data-id': this.campaign_id,
    									'data-href': '#'
    								}).appendTo('#success-wrap #cc-campaigns .row#cc_'+this.campaign_id)
    
    
    								$('<i />', {
    									'class' : 'far fa-save add notadded',
    									'data-id': this.campaign_id,
    									'click' : function () {
    										var campaigns = null;
    										$('#cc_'+$this.campaign_id+' i.add').removeClass('notadded');
    										$('#cc_'+$this.campaign_id+' i.add').siblings('i.link').removeClass('notadded');
    										admin.fn.reg_add_click($this.campaign_id, campaigns)
    
    									}
    								}).appendTo('#success-wrap #cc-campaigns .row#cc_'+this.campaign_id)	
    
    								admin.fn.get_cc_guts(this.campaign_id);
    								
    								if ((parseInt(data.campaigns.length)-parseInt(5)) == parseInt(index)) {
    									
    									$('#cc-campaigns #loader-wrap').addClass('hidden');
    
    									$('.cc-campaigns .link.notadded').on('click', function () {
    										window.open($(this).attr('data-href'));
        									return false;
    									})
    								}
    							}
						   }
						}
					})


				


				} else if (data.error_key == 'unauthorized') {
					$('#btn-wrap #status').html('<span class = "error">It looks like you need to login to constant contacts again</span>')
				}
			}, // end output campaigns cc
			'reg_add_click' : function (id, campaigns) {
				$('div.row#cc_'+id).addClass('added')

				$.each(admin.data.cc_campaigns.campaigns, function () {
					if (this.campaign_id===id) {
						campaigns = this
					} 
				})

				if (campaigns != null) {
					if (admin.data.temp == undefined) {
						admin.data.temp= {};
					}
					admin.data.temp[campaigns.campaign_id] = campaigns;

					if ($('#temp-campaigns div#'+campaigns.campaign_id).length == 0) {

					$('<div />', {
							'id': campaigns.campaign_id,
							'class': 'row'
						}).appendTo('#temp-campaigns .panel-body')

						$('<div />', {
							'class': 'col-lg-5',
							'text': campaigns.name
						}).appendTo('#temp-campaigns #'+campaigns.campaign_id)

						$('<div />', {
							'class': 'col-lg-2',
							'text': campaigns.current_status
						}).appendTo('#temp-campaigns #'+campaigns.campaign_id)
									
						$('<div />', {
							'class': 'col-lg-2',
							'text': new Date(campaigns.updated_at).toLocaleDateString()
						}).appendTo('#temp-campaigns #'+campaigns.campaign_id)

						$('<i />', {
							'data-id': campaigns.campaign_id,
							'class': 'far fa-trash-alt notadded remove'
						}).appendTo('#temp-campaigns #'+campaigns.campaign_id)

						$('#temp-campaigns #'+ campaigns.campaign_id +' i.remove').click(function () {
							
							var id = $(this).attr('data-id');							
							$('div#'+id+'.row').remove()
							delete admin.data.temp[id]
							$('#cc_'+id).removeClass('added')
							
							if (Object.keys(admin.data.temp).length==0) {	
								admin.data.temp= undefined;
							} 
						})

					}
				}
			}
		}, //end fn
		utils : {
			update_campaign_rec: function (obj, id, data) {

				var rec = {};
				 $.each(obj, function () {
					if (this.campaign_id == id) {
						if (data['campaign_activity_id'] != undefined) {
							this['campaign_activity_id'] = data['campaign_activity_id']
						}
						if (data['permalink_url'] != undefined) {
							this['permalink_url'] = data['permalink_url']
						}

						return this;
					}
				})

				return rec;
			},
			lookup_campaign_rec : function (obj, id) {
				var rec;
				 $.each(obj, function () {
					if (this.campaign_id == id || this.cc_id == id) {
						rec = this;
						return this;
					}
				})
				return rec;
			},
			getUrlParam: function () {
				var vars = {};
    			var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        		vars[key] = value;
    			});
    			return vars;
			}
		}
	}

	admin.fn.init(admin);

	if (admin.evars.secure == false) {
		admin.fn.reg_login_btn();
		$("#login input#pin").on('keyup', function (e) {
    		if (e.keyCode === 13) {
        		$('#login #submit').click();
    		}
		});

	} else if (admin.evars.secure == true && window.location.search == "") {
		$('#login-wrap').removeClass('show');
		$('#login-wrap').addClass('hidden');
		window.location.href = admin.evars.auth_url;
	} else if (admin.evars.secure == true && window.location.search.length >0) {
		admin.fn.get_db_campaigns();
	}

	$('#success-wrap').click(function () {

		$('#btn-wrap #status').empty();
	})

	$('#btn-wrap button').click(function () {
		$('#btn-wrap #status').empty();
	})

	$('#btn-wrap #login-cc').click(function () {
		window.location.href = admin.evars.auth_url;
	})

	$('button#reload').click(function () {
		window.location.href = admin.evars.redirect_uri;
	})

	$('button#get-campaigns').click(function () {
	 
		$('#success-wrap #cc-campaigns>.panel-body #result-wrap').empty()
		$('#cc-campaigns #loader-wrap').removeClass('hidden');

		var ssh = admin.evars.secret

		if (ssh!=undefined) {
			$.ajax({
				'url': 'rest/Campaigns.php',
				'type' : 'POST',
				'data' : {'access_token': ssh, 'base_url': admin.evars.base_url},
				'success': function (data) {
					try {
						admin.fn.output_campaigns_cc(data);
					} catch (e) {console.log(e);}
				}
			})
		}
	})

	$('#save-to-db').click(function () {
		if (admin.data.temp != undefined) { 
			$('#myModal').modal('show');
			$('#myModal .modal-title').text('Saving Items To The Website');
			$('#myModal .modal-body').html('');
			
		
			var counter = 0
			$.each(admin.data.temp, function (index, val) {
				counter = counter + 1;
				var html = '<div class = "row" id = "'+this.campaign_id +'"><div class = "col-lg-1 result"></div><div class = "col-lg-5">'+this.name+'</div></div>';
				$('#myModal .modal-body').append(html)
				$.ajax({
					'url': 'rest/save_campaign.php',
					'type': 'POST',
					'data' : {'access_token': admin.evars.secret, 'sessionid': admin.evars.sessionid, 'data': this},
					'success': function (data) {
						data = JSON.parse(data);
						if (data.rowcount >parseInt(0)) {
							$('#myModal #'+ data.cc_id+' .result').html('<i class = "fas fa-check" />')
						} else {
							$('#myModal #'+ data.cc_id+' .result').html('<i class = "fas fa-check-circle" />')
						}

						$('#temp-campaigns #'+data.cc_id).hide();
						$('#cc-campaigns #cc_'+data.cc_id).hide();
						delete admin.data.temp[index];
						admin.fn.get_db_campaigns();
					}
				})
			})
		} else {
			$('#btn-wrap #status').html('<span class = "error">Please click save on at least one from Constant Contact</span>')
			$('#myModal').modal('hide');
		}
	})
})// end doc read