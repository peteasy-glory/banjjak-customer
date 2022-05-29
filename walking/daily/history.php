<?php
include($_SERVER['DOCUMENT_ROOT']."/include/global.php");
include($_SERVER['DOCUMENT_ROOT']."/common/TRestAPI.php");
include($_SERVER['DOCUMENT_ROOT']."/include/skin/header.php");


?>
<!-- header -->
<header id="header">	
	<div class="header-left">
		<a href="#" class="btn-page-ui btn-page-prev"><div class="icon icon-size-24 icon-page-prev">페이지 뒤로가기</div></a>
	</div>
	<div class="page-title">월별 산책기록</div>
</header>
<!-- //header -->

<!-- container -->
<section id="container"> 
	<!-- page-body -->    
	<div class="page-body">
		<!-- page-contents -->  
		<div class="page-contents small">
			<div class="con-title-group">
				<h4 class="con-title">2021.10</h4>
				<!-- 20220115 수정 -->
				<div class="con-title-option">
					<div class="option-cell">20.8Km</div>
					<div class="option-cell">302분</div>
					<div class="option-cell"><div class="icon icon-defecation-gray-small"></div>3회</div>
				</div>
				<!-- //20220115 수정 -->
			</div>
			<div class="record-month-list">
				<ul class="accordion-list">
					<li class="accordion-cell">			
						<button type="button" class="btn-accordion-menu btn-record-accordion">
							<span class="btn-record-accordion-inner">
								<span class="record-accordion-date">2021.09.15 (목) 17:15 ~ 18:30</span>
								<span class="record-accordion-option">2.2Km, 75분</span>
							</span>
						</button>
						<div class="accordion-content">
							<div class="record-accordion-data">
								<div class="record-accordion-detail">이미지영역</div>
								<div class="record-accordion-header">		
									<div class="item-sort">
										<div class="icon icon-size-24 icon-clock-small-white"></div>				
										<div class="item-value">16분</div>
									</div>
									<div class="item-sort">
										<div class="item-value">1.2Km</div>
									</div>
									<div class="item-sort">
										<div class="icon icon-size-24 icon-defecate-small-white"></div>
										<div class="item-value">3회</div>
									</div>
								</div>
								<button type="button" class="btn-record-kakao-share"></button>
							</div>
						</div>			
					</li>   
					<li class="accordion-cell">			
						<button type="button" class="btn-accordion-menu btn-record-accordion">
							<span class="btn-record-accordion-inner">
								<span class="record-accordion-date">2021.09.15 (목) 17:15 ~ 18:30</span>
								<span class="record-accordion-option">2.2Km, 75분</span>
							</span>
						</button>
						<div class="accordion-content">
							<div class="record-accordion-data">
								<div class="record-accordion-detail">이미지영역</div>
								<div class="record-accordion-header">		
									<div class="item-sort">
										<div class="icon icon-size-24 icon-clock-small-white"></div>				
										<div class="item-value">16분</div>
									</div>
									<div class="item-sort">
										<div class="item-value">1.2Km</div>
									</div>
									<div class="item-sort">
										<div class="icon icon-size-24 icon-defecate-small-white"></div>
										<div class="item-value">3회</div>
									</div>
								</div>
								<button type="button" class="btn-record-kakao-share"></button>
							</div>
						</div>			
					</li>   
					<li class="accordion-cell">			
						<button type="button" class="btn-accordion-menu btn-record-accordion">
							<span class="btn-record-accordion-inner">
								<span class="record-accordion-date">2021.09.15 (목) 17:15 ~ 18:30</span>
								<span class="record-accordion-option">2.2Km, 75분</span>
							</span>
						</button>
						<div class="accordion-content">
							<div class="record-accordion-data">
								<div class="record-accordion-detail">이미지영역</div>
								<div class="record-accordion-header">		
									<div class="item-sort">
										<div class="icon icon-size-24 icon-clock-small-white"></div>				
										<div class="item-value">16분</div>
									</div>
									<div class="item-sort">
										<div class="item-value">1.2Km</div>
									</div>
									<div class="item-sort">
										<div class="icon icon-size-24 icon-defecate-small-white"></div>
										<div class="item-value">3회</div>
									</div>
								</div>
								<button type="button" class="btn-record-kakao-share"></button>
							</div>
						</div>			
					</li>   
					<li class="accordion-cell">			
						<button type="button" class="btn-accordion-menu btn-record-accordion">
							<span class="btn-record-accordion-inner">
								<span class="record-accordion-date">2021.09.15 (목) 17:15 ~ 18:30</span>
								<span class="record-accordion-option">2.2Km, 75분</span>
							</span>
						</button>
						<div class="accordion-content">
							<div class="record-accordion-data">
								<div class="record-accordion-detail">이미지영역</div>
								<div class="record-accordion-header">		
									<div class="item-sort">
										<div class="icon icon-size-24 icon-clock-small-white"></div>				
										<div class="item-value">16분</div>
									</div>
									<div class="item-sort">
										<div class="item-value">1.2Km</div>
									</div>
									<div class="item-sort">
										<div class="icon icon-size-24 icon-defecate-small-white"></div>
										<div class="item-value">3회</div>
									</div>
								</div>
								<button type="button" class="btn-record-kakao-share"></button>
							</div>
						</div>			
					</li>   
					<li class="accordion-cell">			
						<button type="button" class="btn-accordion-menu btn-record-accordion">
							<span class="btn-record-accordion-inner">
								<span class="record-accordion-date">2021.09.15 (목) 17:15 ~ 18:30</span>
								<span class="record-accordion-option">2.2Km, 75분</span>
							</span>
						</button>
						<div class="accordion-content">
							<div class="record-accordion-data">
								<div class="record-accordion-detail">이미지영역</div>
								<div class="record-accordion-header">		
									<div class="item-sort">
										<div class="icon icon-size-24 icon-clock-small-white"></div>				
										<div class="item-value">16분</div>
									</div>
									<div class="item-sort">
										<div class="item-value">1.2Km</div>
									</div>
									<div class="item-sort">
										<div class="icon icon-size-24 icon-defecate-small-white"></div>
										<div class="item-value">3회</div>
									</div>
								</div>
								<button type="button" class="btn-record-kakao-share"></button>
							</div>
						</div>			
					</li>   
					<li class="accordion-cell">			
						<button type="button" class="btn-accordion-menu btn-record-accordion">
							<span class="btn-record-accordion-inner">
								<span class="record-accordion-date">2021.09.15 (목) 17:15 ~ 18:30</span>
								<span class="record-accordion-option">2.2Km, 75분</span>
							</span>
						</button>
						<div class="accordion-content">
							<div class="record-accordion-data">
								<div class="record-accordion-detail">이미지영역</div>
								<div class="record-accordion-header">		
									<div class="item-sort">
										<div class="icon icon-size-24 icon-clock-small-white"></div>				
										<div class="item-value">16분</div>
									</div>
									<div class="item-sort">
										<div class="item-value">1.2Km</div>
									</div>
									<div class="item-sort">
										<div class="icon icon-size-24 icon-defecate-small-white"></div>
										<div class="item-value">3회</div>
									</div>
								</div>
								<button type="button" class="btn-record-kakao-share"></button>
							</div>
						</div>			
					</li>   
					<li class="accordion-cell">			
						<button type="button" class="btn-accordion-menu btn-record-accordion">
							<span class="btn-record-accordion-inner">
								<span class="record-accordion-date">2021.09.15 (목) 17:15 ~ 18:30</span>
								<span class="record-accordion-option">2.2Km, 75분</span>
							</span>
						</button>
						<div class="accordion-content">
							<div class="record-accordion-data">
								<div class="record-accordion-detail">이미지영역</div>
								<div class="record-accordion-header">		
									<div class="item-sort">
										<div class="icon icon-size-24 icon-clock-small-white"></div>				
										<div class="item-value">16분</div>
									</div>
									<div class="item-sort">
										<div class="item-value">1.2Km</div>
									</div>
									<div class="item-sort">
										<div class="icon icon-size-24 icon-defecate-small-white"></div>
										<div class="item-value">3회</div>
									</div>
								</div>
								<button type="button" class="btn-record-kakao-share"></button>
							</div>
						</div>			
					</li>   
					<li class="accordion-cell">			
						<button type="button" class="btn-accordion-menu btn-record-accordion">
							<span class="btn-record-accordion-inner">
								<span class="record-accordion-date">2021.09.15 (목) 17:15 ~ 18:30</span>
								<span class="record-accordion-option">2.2Km, 75분</span>
							</span>
						</button>
						<div class="accordion-content">
							<div class="record-accordion-data">
								<div class="record-accordion-detail">이미지영역</div>
								<div class="record-accordion-header">		
									<div class="item-sort">
										<div class="icon icon-size-24 icon-clock-small-white"></div>				
										<div class="item-value">16분</div>
									</div>
									<div class="item-sort">
										<div class="item-value">1.2Km</div>
									</div>
									<div class="item-sort">
										<div class="icon icon-size-24 icon-defecate-small-white"></div>
										<div class="item-value">3회</div>
									</div>
								</div>
								<button type="button" class="btn-record-kakao-share"></button>
							</div>
						</div>			
					</li>   
					<li class="accordion-cell">			
						<button type="button" class="btn-accordion-menu btn-record-accordion">
							<span class="btn-record-accordion-inner">
								<span class="record-accordion-date">2021.09.15 (목) 17:15 ~ 18:30</span>
								<span class="record-accordion-option">2.2Km, 75분</span>
							</span>
						</button>
						<div class="accordion-content">
							<div class="record-accordion-data">
								<div class="record-accordion-detail">이미지영역</div>
								<div class="record-accordion-header">		
									<div class="item-sort">
										<div class="icon icon-size-24 icon-clock-small-white"></div>				
										<div class="item-value">16분</div>
									</div>
									<div class="item-sort">
										<div class="item-value">1.2Km</div>
									</div>
									<div class="item-sort">
										<div class="icon icon-size-24 icon-defecate-small-white"></div>
										<div class="item-value">3회</div>
									</div>
								</div>
								<button type="button" class="btn-record-kakao-share"></button>
							</div>
						</div>			
					</li>   
					<li class="accordion-cell">			
						<button type="button" class="btn-accordion-menu btn-record-accordion">
							<span class="btn-record-accordion-inner">
								<span class="record-accordion-date">2021.09.15 (목) 17:15 ~ 18:30</span>
								<span class="record-accordion-option">2.2Km, 75분</span>
							</span>
						</button>
						<div class="accordion-content">
							<div class="record-accordion-data">
								<div class="record-accordion-detail">이미지영역</div>
								<div class="record-accordion-header">		
									<div class="item-sort">
										<div class="icon icon-size-24 icon-clock-small-white"></div>				
										<div class="item-value">16분</div>
									</div>
									<div class="item-sort">
										<div class="item-value">1.2Km</div>
									</div>
									<div class="item-sort">
										<div class="icon icon-size-24 icon-defecate-small-white"></div>
										<div class="item-value">3회</div>
									</div>
								</div>
								<button type="button" class="btn-record-kakao-share"></button>
							</div>
						</div>			
					</li>   
					<li class="accordion-cell">			
						<button type="button" class="btn-accordion-menu btn-record-accordion">
							<span class="btn-record-accordion-inner">
								<span class="record-accordion-date">2021.09.15 (목) 17:15 ~ 18:30</span>
								<span class="record-accordion-option">2.2Km, 75분</span>
							</span>
						</button>
						<div class="accordion-content">
							<div class="record-accordion-data">
								<div class="record-accordion-detail">이미지영역</div>
								<div class="record-accordion-header">		
									<div class="item-sort">
										<div class="icon icon-size-24 icon-clock-small-white"></div>				
										<div class="item-value">16분</div>
									</div>
									<div class="item-sort">
										<div class="item-value">1.2Km</div>
									</div>
									<div class="item-sort">
										<div class="icon icon-size-24 icon-defecate-small-white"></div>
										<div class="item-value">3회</div>
									</div>
								</div>
								<button type="button" class="btn-record-kakao-share"></button>
							</div>
						</div>			
					</li>      
				</ul>
			</div>
		</div>
		<!-- //page-contents -->  
	</div>
	<!-- //page-body -->    

</section>
<!-- //container -->

	
</body>
</html>