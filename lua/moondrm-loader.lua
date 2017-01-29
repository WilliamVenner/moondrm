local function MoonDRM_Load(...)
	local va = {...}
	local RunString = RunString local http = http local debug = debug local timer = timer local file = nil
	if (not MoonDRM) then
	    timer.Simple(0,function()
	    	if (debug.getinfo(RunString).short_src ~= "[C]") then return end
	    	if (debug.getinfo(http.Fetch).short_src ~= "lua/includes/modules/http.lua") then return end
	    	if (debug.getinfo(sql.Query).short_src ~= "[C]") then return end
	    	sql.Query("CREATE TABLE IF NOT EXISTS `moondrm` (`script_id` int NOT NULL,`version` varchar NULL,`code` text NOT NULL,PRIMARY KEY (`script_id`,`version`))")
	    	local function err(e)
	    		local core = sql.Query("SELECT `code` FROM `moondrm` WHERE `script_id`=-1")
	    		if (not core) then
	    			if (e) then
	    				MsgC(Color(255,0,0),"[MoonDRM] ",Color(255,255,255),"We couldn't connect to MoonDRM. Please visit https://moondrm.com/error/1 (Error code: " .. tostring(e) .. ")\n")
					else
	    				MsgC(Color(255,0,0),"[MoonDRM] ",Color(255,255,255),"We couldn't connect to MoonDRM. Please visit https://moondrm.com/error/1\n")
	    			end
				else
					RunString(core[1].code,"MoonDRM Core")
					MoonDRM(unpack(va))
				end
	    	end
	    	http.Fetch("https://api.moondrm.com/loader/",function(code)
	    		if (not code:find("^--#MoonDRM#")) then
	    			err()
	    			file.Write("moondrm_error.txt",util.Base64Encode(code))
	    		else
					code = code:gsub("^--#MoonDRM#","")
	    			RunString(code,"MoonDRM Core")
					MoonDRM(unpack(va))
	    		end
	    	end,err)
	    end)
	else
		MoonDRM(...)
	end
end